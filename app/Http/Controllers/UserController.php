<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Http\Request;
use Log;

class UserController extends Controller
{
   private User $user;
   private Company $company;

   public function __construct(User $user, Company $company) {
      $this->user = $user;
      $this->company = $company;
      $this->setSession();
   }

   public function index() {

      $users = $this->user
         ->select(['id', 'name', 'email', 'active', 'created_at'])
         ->with(['companies' => function ($query) {
            $query->select(['id', 'cnpj', 'user_id', 'name', 'active'])
               ->with(['integrationSettings:id,company_id,linx_company,linx_resale'])
               ->orderBy('name');
         }])
         ->where('admin', false)
         ->get();

      $users->transform(function($user) {

         $user->companies->makeHidden('user_id');

         $user->companies->each(function($company) {

            if($company->integrationSettings) {
               $company->linx_resale  = $company->integrationSettings->linx_resale;
               $company->linx_company = $company->integrationSettings->linx_company;
            }

            unset($company->integrationSettings);
         });

         return $user;
      });

      return response()->json($users);
   }

   public function store(Request $request) {

      $data = $request->validate([
         'name' => 'required|string|max:100',
         'email' => 'required|string|max:100|unique:users,email',
         'password' => 'required|string|max:18',
         'logo_url' => 'required|max:500|url:https',
         'company' => 'required|array',
         'company.name' => 'required|string|max:100',
         'company.cnpj' => 'required|string|size:14',
      ]);

      $user = [
         'name' => $data['name'],
         'email' => $data['email'],
         'logo_url' => $data['logo_url'],
         'password' => Hash::make($data['password']),
         'active' => true,
         'admin' => false,
      ];

      try {

         DB::beginTransaction();

         $createdUser = $this->user->create($user);

         $company = [
            'user_id' => $createdUser->id,
            'name' => $data['company']['name'],
            'cnpj' => $data['company']['cnpj'],
            'primary' => true,
            'active'  => true,
         ];

         $this->company->create($company);

         DB::commit();
         return response(status: 204);

      } catch (\Exception $e) {
         Log::error($e->getMessage());
         DB::rollBack();
         return response()->json(['message' => 'Erro interno no servidor'], 500);
      }

   }

   public function updateActive(Request $request, int $userId) {

      $active = $request->input('active', true);

      if($userId === $this->session->userId)
         return abort(400, 'Não é possível atualizar o seu próprio status');

      try {

         $this->user
            ->where('id', $userId)
            ->update([
               'active' => $active
            ]);

         return response(status: 204);

      } catch (\Exception $e) {
         Log::error($e->getMessage());
         return response()->json(['message' => 'Erro interno no servidor'], 500);
      }

   }

   public function updateCompanyActive(Request $request, int $userId, int $companyId) {

      $active = $request->input('active', true);

      try {

         $this->company
            ->where([
               'id' => $companyId,
               'user_id' => $userId,
            ])
            ->update([
               'active' => $active
            ]);

         return response(status: 204);

      } catch (\Exception $e) {
         Log::error($e->getMessage());
         return response()->json(['message' => 'Erro interno no servidor'], 500);
      }

   }

}
