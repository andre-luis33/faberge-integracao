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
