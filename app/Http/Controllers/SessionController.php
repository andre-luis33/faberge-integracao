<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
   public function __construct() {
      $this->setSession();
   }

   public function index() {
      $session = session()->get('user');
      return response()->json($session);
   }

   public function sidebar(Request $request) {
      $closed = (bool) $request->input('closed');
      session()->put('user.sidebar.closed', $closed);
      return response(status: 204);
   }

   public function activeCompany(int $companyId) {
      $companies = collect($this->session->companies);
      $choosenCompany = $companies->where('id', $companyId)->first();

      if(!$choosenCompany)
         abort(400, 'A empresa selecionada não existe!');
      else if ($choosenCompany->id === $this->session->company->id)
         return response(status: 204);

      session()->put('user.company.id', $choosenCompany->id);
      session()->put('user.company.name', $choosenCompany->name);
      session()->put('user.company.cnpj', $choosenCompany->cnpj);

      return response(status: 204);
   }
}
