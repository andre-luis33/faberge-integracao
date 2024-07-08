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
      $companyId = $this->session->company->id;

      $parts = $this->partGroup
         ->select(['category', 'type'])
         ->where('company_id', $companyId)
         ->get();

      return response()->json($parts);
   }

   public function update(Request $request) {

      $validation = [
         '*.category' => 'required|string|max:100',
         '*.type' => 'required|string|in:Genuína,Original,Verde,Outras Fontes'
      ];

      $request->validate($validation);
      $companyId = $this->session->company->id;

      $partGroups = collect($request->all());

      try {

         DB::beginTransaction();

         $this->partGroup
            ->where('company_id', $companyId)
            ->delete();

         $partGroups->each(function($partGroup) use ($companyId) {

            $this->partGroup->create([
               'category'   => $partGroup['category'],
               'type' => $partGroup['type'],
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
