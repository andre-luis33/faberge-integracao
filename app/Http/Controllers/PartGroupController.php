<?php

namespace App\Http\Controllers;

use App\Models\PartGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartGroupController extends Controller
{
   private PartGroup $partGroup;

   public function __construct(PartGroup $partGroup) {
      $this->partGroup = $partGroup;
      $this->setSession();
   }

   public function index() {
      $userId = $this->session->userId;

      $parts = $this->partGroup
         ->select(['category', 'type'])
         ->where('user_id', $userId)
         ->get();

      return response()->json($parts);
   }

   public function update(Request $request) {

      $validation = [
         '*.category' => 'required|string|max:100',
         '*.type' => 'required|string|in:Genuína,Original,Verde,Outras Fontes'
      ];

      $request->validate($validation);
      $userId = $this->session->userId;

      $partGroups = collect($request->all());

      try {

         DB::beginTransaction();

         $this->partGroup
            ->where('user_id', $userId)
            ->delete();

         $partGroups->each(function($partGroup) use ($userId) {

            $this->partGroup->create([
               'category'   => $partGroup['category'],
               'type' => $partGroup['type'],
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
