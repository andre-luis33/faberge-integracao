<?php

namespace App\Http\Controllers;

use App\Business\StockUpdateBusiness;
use App\Exceptions\BusinessException;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{

   private StockUpdateBusiness $stockUpdateBusiness;

   public function __construct(StockUpdateBusiness $stockUpdateBusiness) {
      $this->stockUpdateBusiness = $stockUpdateBusiness;
      $this->setSession();
   }

   public function store() {

      $companyId = $this->session->company->id;

      try {

         $this->stockUpdateBusiness->updateStock($companyId, true);
         return response()->json(['message' => 'Integração executada com sucesso'], 200);

      } catch (BusinessException $e) {
         abort($e->getCode(), $e->getMessage());
      } catch (\Exception $e) {
         abort(500, 'Erro interno no servidor');
      }

   }
}
