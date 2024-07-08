<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTime;
use App\Utils\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DeliveryTimeController extends Controller
{

   private DeliveryTime $deliveryTime;

   public function __construct(DeliveryTime $deliveryTime) {
      $this->deliveryTime = $deliveryTime;
      $this->setSession();
   }

   public function index() {
      $companyId = $this->session->company->id;

      $times = $this->deliveryTime
         ->select(['uf', 'days'])
         ->where('company_id', $companyId)
         ->get();

      return response()->json($times);
   }

   public function update(Request $request) {

      $validation = [
         '*.uf' => 'required|string|size:2',
         '*.days' => 'nullable|integer|min:1'
      ];

      $request->validate($validation);
      $companyId = $this->session->company->id;

      $requestUfs = collect($request->all());

      // Verifica se todas as UFs estão presentes no payload
      $ufs = Data::getUfs()->keys();
      $requestUfsKeys = $requestUfs->pluck('uf');

      if ($requestUfsKeys->count() != $ufs->count())
         return abort(400, 'Por favor, envie valores para todas as ' . $ufs->count() . ' UFs existentes no Brasil');

      try {

         DB::beginTransaction();

         $this->deliveryTime
            ->where('company_id', $companyId)
            ->delete();

         $requestUfs->each(function($uf) use ($companyId, $ufs) {

            if(!$ufs->contains($uf['uf']))
               return;

            $this->deliveryTime->create([
               'uf'   => $uf['uf'],
               'days' => $uf['days'],
               'company_id' => $companyId
            ]);

         });

         DB::commit();
         return response(status: 204);

      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }

   }

}
