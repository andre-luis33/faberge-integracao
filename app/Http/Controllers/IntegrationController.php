<?php

namespace App\Http\Controllers;

use App\Business\IntegrationBusiness;
use App\Exceptions\BusinessException;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{

   private IntegrationBusiness $integrationBusiness;

   public function __construct(IntegrationBusiness $integrationBusiness) {
      $this->integrationBusiness = $integrationBusiness;
      $this->setSession();
   }

   public function index() {
      $companyId = $this->session->company->id;
      $integrations = $this->integrationBusiness->findLastExecutions($companyId);
      return response()->json($integrations);
   }

   public function downloadCsv(int $executionId) {
      $companyId = $this->session->company->id;

      try {

         $csv = $this->integrationBusiness->getCiliaCsvByExecutionIdAndCompanyId($executionId, $companyId);
         return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="estoque-cilia.csv"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');

      } catch (BusinessException $e) {
         abort(400, 'Execução não encontrada');
      } catch (\Exception $e) {
         abort(500, 'Erro interno no servidor');
      }
   }

   public function store() {

      $companyId = $this->session->company->id;

      try {

         $this->integrationBusiness->executeIntegration($companyId, true);
         return response()->json(['message' => 'Integração executada com sucesso'], 200);

      } catch (BusinessException $e) {
         abort($e->getCode(), $e->getMessage());
      } catch (\Exception $e) {
         abort(500, 'Erro interno no servidor');
      }

   }
}
