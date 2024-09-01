<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{

   private Company $company;

   public function __construct(Company $company) {
      $this->company = $company;
      $this->setSession();
   }

   public function index() {
      $userId = $this->session->userId;
      $companies = $this->company
         ->select(['c.*'])
         ->from('companies as c')
         ->where('c.user_id', $userId)
         ->orderBy('c.name')
         ->get()
         ->makeHidden('c.user_id');

      $companies->each(function($company) {
         $company->last_execution_successful = $company->cilia_status_code == 204;
         unset($company->cilia_status_code);
      });

      return response()->json($companies);
   }

   public function store(Request $request) {

      $userId = $this->session->userId;

      $company = $request->validate([
         'cnpj' => [
            'required', 'size:14',
            Rule::unique('companies')->where('user_id', $userId)
         ],
         'name' => 'required|string|max:100',
         'primary' => 'required|boolean',
      ]);

      $company['user_id'] = $userId;

      try {

         DB::beginTransaction();

         if($company['primary']) {
            $this->company
               ->where('user_id', $userId)
               ->update([ 'primary' => false ]);
         }

         $createdCompany = $this->company->create($company);
         DB::commit();

         $companies = session()->get('user.companies');
         $companies[] = [
            'id' => $createdCompany->id,
            'name' => $createdCompany->name,
            'cnpj' => $createdCompany->cnpj,
         ];

         session()->put('user.company.id',   $createdCompany->id);
         session()->put('user.company.name', $createdCompany->name);
         session()->put('user.company.cnpj', $createdCompany->cnpj);
         session()->put('user.companies',    $companies);

         return response()->json($createdCompany, 201);

      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }

   }

   public function update(Request $request, int $companyId) {

      $userId = $this->session->userId;

      $company = $request->validate([
         'cnpj' => [
            'required', 'size:14',
            Rule::unique('companies')->where('user_id', $userId)->ignore($companyId)
         ],
         'name' => 'required|string|max:100',
         'primary' => 'required|boolean',
         'active' => 'required|boolean',
      ]);

      $company['user_id'] = $userId;

      // if(!$company['primary'])
      //    abort(400, 'Você não pode')

      if($company['primary'] && !$company['active'])
         abort(400, 'Você não pode desativar a empresa primária!');

      try {

         DB::beginTransaction();

         if($company['primary']) {
            $this->company
               ->where('user_id', $userId)
               ->update([ 'primary' => false ]);
         }

         $this->company
            ->where('id', $companyId)
            ->update($company);

         // missing session validation

         DB::commit();
         return response()->json([], 204);

      } catch (Exception $e) {
         DB::rollBack();
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }

   }

}
