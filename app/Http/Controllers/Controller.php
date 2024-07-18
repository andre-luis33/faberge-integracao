<?php

namespace App\Http\Controllers;

use App\Dtos\SessionCompanyDto;
use App\Dtos\SessionDto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
   use AuthorizesRequests, ValidatesRequests;

   public SessionDto $session;

   public function setSession()
   {
      $this->middleware(function ($request, $next) {

         if (session()->get('user')) {

            $company = new SessionCompanyDto(
               (int) session()->get('user.company.id'),
               session()->get('user.company.name'),
               session()->get('user.company.cnpj')
            );

            $companies = [];
            foreach(session()->get('user.companies') as $sessionCompany) {
               $companies[] = new SessionCompanyDto(
                  (int) $sessionCompany['id'],
                  $sessionCompany['name'],
                  $sessionCompany['cnpj'],
               );
            }

            $this->session = new SessionDto(
               session()->get('user.id'),
               session()->get('user.name'),
               session()->get('user.email'),
               $company,
               $companies,
               (bool) session()->get('user.sidebar.closed'),
            );
         }

         return $next($request);
      });
   }
}
