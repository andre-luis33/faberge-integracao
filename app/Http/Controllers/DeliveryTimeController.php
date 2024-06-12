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
      $userId = $this->session->userId;

      $times = $this->deliveryTime
         ->select(['uf', 'days'])
         ->where('user_id', $userId)
         ->get();

      return response()->json($times);
   }

   public function update(Request $request) {

      $validation = [
         '*.uf' => 'required|string|size:2',
         '*.days' => 'nullable|integer|min:1'
      ];

      $request->validate($validation);
      $userId = $this->session->userId;

      $requestUfs = collect($request->all());

      // Verifica se todas as UFs estão presentes no payload
      $ufs = Data::getUfs()->keys();
      $requestUfsKeys = $requestUfs->pluck('uf');

      if ($requestUfsKeys->count() != $ufs->count())
         return abort(400, 'Por favor, envie valores para todas as ' . $ufs->count() . ' UFs existentes no Brasil');

      try {

         DB::beginTransaction();

         $this->deliveryTime
            ->where('user_id', $userId)
            ->delete();

         $requestUfs->each(function($uf) use ($userId, $ufs) {

            if(!$ufs->contains($uf['uf']))
               return;

            $this->deliveryTime->create([
               'uf'   => $uf['uf'],
               'days' => $uf['days'],
               'user_id' => $userId
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
