<?php

namespace App\Business;
use App\Dtos\CiliaStockItemDto;
use App\Dtos\LinxStockPartDto;
use App\Exceptions\BusinessException;
use App\Models\DeliveryTime;
use App\Models\IntegrationExecution;
use App\Models\IntegrationSettings;
use App\Models\PartGroup;
use App\Services\CiliaService;
use App\Services\LinxService;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Error;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Illuminate\Database\Eloquent\Collection as DbCollection;

class IntegrationBusiness {

   private DeliveryTime $deliveryTime;
   private PartGroup $partGroup;
   private IntegrationSettings $integrationSettings;
   private IntegrationExecution $integrationExecution;
   private LinxService $linxService;
   private CiliaService $ciliaService;

   public function __construct(DeliveryTime $deliveryTime, PartGroup $partGroup, IntegrationSettings $integrationSettings, IntegrationExecution $integrationExecution, LinxService $linxService, CiliaService $ciliaService) {
      $this->deliveryTime = $deliveryTime;
      $this->partGroup = $partGroup;
      $this->integrationSettings = $integrationSettings;
      $this->integrationExecution = $integrationExecution;
      $this->linxService = $linxService;
      $this->ciliaService = $ciliaService;
   }

   /**
    * Searchs for all integrations in the past 24 hours and treats the error. When it's an app error, it provides a generic message for security reasons
    * @return
    */
   public function findLastExecutions(int $companyId) {
      $date = date('Y-m-d H:i:s', strtotime('-24 hours'));
      $integrations = $this->integrationExecution
         ->select([
            'id',
            'linx_status_code',
            'cilia_status_code',
            'error',
            'forced_execution',
            'is_internal_error',
            'created_at',
            'start_time',
            'end_time',
            DB::raw('
              CASE
                 WHEN cilia_payload IS NOT NULL THEN 1
                 ELSE 0
              END as has_cilia_payload
            ')
         ])
         ->where([
            'company_id' => $companyId,
            ['created_at', '>=', $date]
         ])
         ->orderByDesc('created_at')
         ->get();

      $integrations->each(function ($integration) {
         if($integration->is_internal_error)
            $integration->error = 'Erro interno no sistema. Consulte o suporte para obter mais informações sobre o problema';

         $integration->has_cilia_payload = (bool) $integration->has_cilia_payload;

         $startTime = new DateTime($integration->start_time);
         $endTime = new DateTime($integration->end_time);
         unset($integration->start_time);
         unset($integration->end_time);

         $integration->duration = $endTime->diff($startTime)->format('%H:%M:%S');
      });


      return $integrations;
   }

   public function findIntegrationsToRun() {
      return $this->integrationSettings->findIntegrationsToRun();
   }

   public function getCiliaCsvByExecutionIdAndCompanyId(int $executionId, int $companyId): string {

      $ciliaPayload = $this->integrationExecution
         ->select('cilia_payload')
         ->from('integration_executions')
         ->where([
            'company_id' => $companyId,
            'id' => $executionId
         ])
         ->first();

      if(!$ciliaPayload) {
         throw new BusinessException('Execução não encontrada');
      }

      return $ciliaPayload->cilia_payload;
   }

   /**
    * Executes the stock update integration
    * @return void
    */
   public function executeIntegration(int $companyId, bool $forcedExecution): void {

      $csvPath = '';
      $startTime = new DateTime();

      $integrationExecution = [];
      $integrationExecution['company_id'] = $companyId;
      $integrationExecution['forced_execution'] = $forcedExecution;
      $integrationExecution['is_internal_error'] = false;
      $integrationExecution['start_time'] = $startTime;

      try {

         $integrationSettings = $this->integrationSettings->where('company_id', $companyId)->first();
         if(!$integrationSettings)
            throw new BusinessException('Não é possível realizar a integração sem configurar seus parâmetros', 400);

         $allLinxFieldsHaveValue =
            $integrationSettings->linx_user &&
            $integrationSettings->linx_password &&
            $integrationSettings->linx_auth_key &&
            $integrationSettings->linx_stock_key &&
            $integrationSettings->linx_environment &&
            $integrationSettings->linx_company &&
            $integrationSettings->linx_resale
         ;

         if(!$allLinxFieldsHaveValue)
            throw new BusinessException('Não é possível realizar a integração com a Linx sem um usuário, senha, subscription keys, ambiente, empresa e revenda', 400);

         if(!$integrationSettings->cilia_token)
            throw new BusinessException('Não é possível realizar a integração com a Cilia sem um token de autenticação', 400);

         $deliveryTimes = $this->deliveryTime->findStatesWithDeliveryByCompanyId($companyId);
         if($deliveryTimes->count() < 1)
            throw new BusinessException('Não é possível realizar a integração sem prazo de entrega para pelo menos um estado');

         $partGroups = $this->partGroup->where('company_id', $companyId)->get();

         $linxUser        = $integrationSettings->linx_user;
         $linxEnvironment = $integrationSettings->linx_environment;
         $linxCompany     = $integrationSettings->linx_company;
         $linxResale      = $integrationSettings->linx_resale;
         $linxPassword = Crypt::decrypt($integrationSettings->linx_password);
         $linxAuthKey  = Crypt::decrypt($integrationSettings->linx_auth_key);
         $linxStockKey = Crypt::decrypt($integrationSettings->linx_stock_key);

         $this->linxService->setEnvironment($linxEnvironment);
         $linxAuthResponse = $this->linxService->auth($linxAuthKey, $linxUser, $linxPassword);

         $integrationExecution['linx_status_code'] = $linxAuthResponse->statusCode;
         $integrationExecution['linx_response'] = $linxAuthResponse->json;

         if($linxAuthResponse->statusCode != 200)
            throw new BusinessException("Erro ao se autenticar na linx! Status HTTP: {$linxAuthResponse->statusCode} | Resposta API: {$linxAuthResponse->json}", $linxAuthResponse->statusCode);

         $linxResponse = $this->linxService->getStock($linxStockKey, $linxCompany, $linxResale);

         $integrationExecution['linx_status_code'] = $linxResponse->statusCode;
         $integrationExecution['linx_response'] = $linxResponse->json;

         if($linxResponse->statusCode !== 200)
            throw new BusinessException("Erro ao buscar dados de estoque na linx! Status HTTP: {$linxResponse->statusCode} | Resposta API: {$linxResponse->json}", $linxResponse->statusCode);


         $parts = $linxResponse->stockParts;
         $stockParts = $this->mapStockPartsToEachDeliveryState($parts, $deliveryTimes, $partGroups);

         $csvPath = $this->createCsv($stockParts);
         $csvContent = file_get_contents($csvPath);

         $this->ciliaService->setAuthToken(Crypt::decrypt($integrationSettings->cilia_token));
         $ciliaResponse = $this->ciliaService->sendCsv($csvPath, true);

         $integrationExecution['cilia_payload']     = $csvContent;
         $integrationExecution['cilia_status_code'] = $ciliaResponse->statusCode;
         $integrationExecution['cilia_response']    = $ciliaResponse->json;

         if($ciliaResponse->statusCode !== 204)
            throw new BusinessException("Erro ao enviar CSV para cilia! Status HTTP: {$ciliaResponse->statusCode} | Resposta API: {$ciliaResponse->json}", $ciliaResponse->statusCode);

      } catch (BusinessException $e) {

         $integrationExecution['error'] = $e->getMessage();
         Log::error($e->getMessage());
         throw new BusinessException($e->getMessage(), $e->getCode());

      } catch (ConnectException $e) {

         $integrationExecution['error'] = 'Timeout ao se comunicar com a api: '.$e->getMessage();
         Log::error($e->getMessage());
         throw new BusinessException($e->getMessage(), 503);

      // } catch (Exception | Error $e) {
      } catch (Exception $e) {

         $integrationExecution['is_internal_error'] = true;
         $integrationExecution['error'] = $e->getMessage();
         Log::error($e->getMessage());
         throw new Exception($e->getMessage());

      } finally {
         $endTime = new DateTime();
         $integrationExecution['end_time'] = $endTime;

         $this->deleteCsv($csvPath);
         $this->integrationExecution->create($integrationExecution);
      }

   }

   /**
    * @param Collection<LinxStockPartDto>
    * @return Collection<CiliaStockItemDto>
    */
   private function mapStockPartsToEachDeliveryState(Collection $parts, DbCollection $deliveryTimes, DbCollection $partGroups) {

      return $parts->flatMap(function(LinxStockPartDto $part) use ($deliveryTimes, $partGroups) {

         return $deliveryTimes->map(function($deliveryTime) use ($part, $partGroups) {
            // $partGroup     = $partGroups->where('category', $part->GrupoDaMontadora)->first();
            // $partGroupType = $partGroup ? $partGroup->type : 'Outras Fontes';
            $partGroupType = 'Genuína';

            return new CiliaStockItemDto(
               brand:         $part->NomeMarca,
               code:          $part->ItemEstoque,
               name:          $part->DescricaoItemEstoque,
               price:         $part->PrecoSugerido,
               stockQuantity: $part->QuantidadeDisponivel,
               sku:           '',
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

      $allWentGood = true;

      // adding csv header
      $stockParts->prepend([
         'MARCA', 'CODIGO', 'NOME', 'PRECO', 'PRAZO DE ENTREGA', 'ESTOQUE', 'ESTADO', 'SKU', 'TIPO DE PECA'
      ]);

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
