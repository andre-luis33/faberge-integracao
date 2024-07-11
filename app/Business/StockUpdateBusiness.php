<?php

namespace App\Business;
use App\Dtos\CiliaStockItemDto;
use App\Dtos\LinxStockPartDto;
use App\Exceptions\BusinessException;
use App\Models\DeliveryTime;
use App\Models\IntegrationLog;
use App\Models\IntegrationSettings;
use App\Models\PartGroup;
use App\Services\CiliaService;
use App\Services\LinxService;
use Error;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use \Illuminate\Database\Eloquent\Collection as DbCollection;

class StockUpdateBusiness {

   private DeliveryTime $deliveryTime;
   private PartGroup $partGroup;
   private IntegrationSettings $integrationSettings;
   private IntegrationLog $integrationLog;
   private LinxService $linxService;
   private CiliaService $ciliaService;

   public function __construct(DeliveryTime $deliveryTime, PartGroup $partGroup, IntegrationSettings $integrationSettings, IntegrationLog $integrationLog, LinxService $linxService, CiliaService $ciliaService) {
      $this->deliveryTime = $deliveryTime;
      $this->partGroup = $partGroup;
      $this->integrationSettings = $integrationSettings;
      $this->integrationLog = $integrationLog;
      $this->linxService = $linxService;
      $this->ciliaService = $ciliaService;
   }

   public function updateStock(int $companyId, bool $forcedExecution): void {

      $csvPath = '';
      $integrationLog = [];
      $integrationLog['company_id'] = $companyId;
      $integrationLog['forced_execution'] = $forcedExecution;

      try {

         $integrationSettings = $this->integrationSettings->where('company_id', $companyId)->first();

         if(!$integrationSettings->linx_user || !$integrationSettings->linx_password)
            throw new BusinessException('Não é possível realizar a integração sem um usuário e senha para a api da Linx');

         if(!$integrationSettings->cilia_token)
            throw new BusinessException('Não é possível realizar a integração sem um token de autenticação para a api da Cilia');


         $deliveryTimes       = $this->deliveryTime->findStatesWithDeliveryByCompanyId($companyId);
         $partGroups          = $this->partGroup->where('company_id', $companyId)->get();

         $linxUser     = $integrationSettings->linx_user;
         $linxPassword = Crypt::decrypt($integrationSettings->linx_password);

         $this->linxService->auth(env('LINX_SUBSCRIPTION_KEY'), $linxUser, $linxPassword);

         $linxResponse = $this->linxService->getMockStock();

         if($linxResponse->statusCode !== 200)
            throw new BusinessException("Erro ao buscar dados de estoque na linx! Status HTTP: {$linxResponse->statusCode}", $linxResponse->statusCode);

         $integrationLog['linx_status_code'] = $linxResponse->statusCode;
         $integrationLog['linx_response'] = $linxResponse->json;

         $parts = $linxResponse->stockParts;
         $stockParts = $this->mapStockPartsToEachDeliveryState($parts, $deliveryTimes, $partGroups);

         $csvPath = $this->createCsv($stockParts);
         $csvContent = file_get_contents($csvPath);

         $this->ciliaService->setAuthToken(Crypt::decrypt($integrationSettings->cilia_token));
         $ciliaResponse = $this->ciliaService->sendCsv($csvPath, true);

         $integrationLog['cilia_payload']     = $csvContent;
         $integrationLog['cilia_status_code'] = $ciliaResponse->statusCode;
         $integrationLog['cilia_response']    = $ciliaResponse->json;

         if($ciliaResponse->statusCode !== 204)
            throw new BusinessException("Erro ao enviar CSV para cilia! Status HTTP: {$ciliaResponse->statusCode}", $ciliaResponse->statusCode);

      } catch (BusinessException $e) {

         $integrationLog['app_error'] = $e->getMessage();
         Log::error($e->getMessage());
         throw new BusinessException($e->getMessage(), $e->getCode());

      // } catch (Exception | Error $e) {
      } catch (Exception $e) {

         $integrationLog['app_error'] = $e->getMessage();
         Log::error($e->getMessage());
         throw new Exception($e->getMessage());

      } finally {
         $this->deleteCsv($csvPath);
         $this->integrationLog->create($integrationLog);
      }

   }

   /**
    * @param Collection<LinxStockPartDto>
    * @return Collection<CiliaStockItemDto>
    */
   private function mapStockPartsToEachDeliveryState(Collection $parts, DbCollection $deliveryTimes, DbCollection $partGroups) {

      return $parts->flatMap(function(LinxStockPartDto $part) use ($deliveryTimes, $partGroups) {

         return $deliveryTimes->map(function($deliveryTime) use ($part, $partGroups) {
            $partGroup     = $partGroups->where('category', $part->GrupoDaMontadora)->first();
            $partGroupType = $partGroup ? $partGroup->type : 'Outras Fontes';

            return new CiliaStockItemDto(
               brand:         $part->NomeMarca,
               code:          $part->CodigoEstoque,
               name:          $part->DescricaoItemEstoque,
               price:         $part->Preco,
               stockQuantity: $part->QuantidadeDisponivel,
               sku:           $part->CodigoEanGtin,
               state:         $deliveryTime->uf,
               deliveryTime:  $deliveryTime->days,
               partGroupType: $partGroupType
            );
         });

      });

   }

   /**
    * @param \Illuminate\Support\Collection<CiliaStockItemDto> $csvStock
    */
   private function createCsv(Collection $stockParts): string {

      $filePath= storage_path('app/').time() . 'stock' . rand(0, 100) . '.csv';
      $file = fopen($filePath, 'w');

      $teste = ['vasco', 'flamengo', 'fluminense'];
      $allWentGood = true;

      $stockParts->each(function ($part) use ($file, &$allWentGood) {
         if(!fputcsv($file, (array) $part, ','))
            $allWentGood = false;
      });

      fclose($file);

      if(!$allWentGood) {
         unlink($filePath);
         throw new Exception('Error creating temp csv file');
      }

      return $filePath;
   }

   private function deleteCsv(string $csvPath) {
      if(!$csvPath || !file_exists($csvPath))
         return;

      unlink($csvPath);
   }

}
