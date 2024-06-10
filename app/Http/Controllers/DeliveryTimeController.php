<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTime;
use App\Utils\Data;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeliveryTimeController extends Controller
{

   private DeliveryTime $deliveryTime;

   public function __construct(DeliveryTime $deliveryTime) {
      $this->deliveryTime = $deliveryTime;
   }

   public function index() {
      $userId = session()->get('user.id');

      $times = $this->deliveryTime
         ->select(['uf', 'days'])
         ->where('user_id', $userId)
         ->get();

      return response()->json($times);
   }

   public function update(Request $request) {

      $validationArray = [];
      $ufs = Data::getUfs()->keys();

      $ufs->each(function($uf) use (&$validationArray) {
         $validationArray[$uf] = 'nullable|integer|gt:0';
      });

      $request->validate($validationArray);
      $userId = session()->get('user.id');

      $requestUfs = collect($request->all());

      try {

         DB::beginTransaction();

         $this->deliveryTime
            ->where('user_id', $userId)
            ->delete();

         $requestUfs->each(function($value, $key) use ($userId, $ufs) {

            if(!$ufs->contains($key))
               return;

            $this->deliveryTime->create([
               'uf'   => $key,
               'days' => $value,
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
