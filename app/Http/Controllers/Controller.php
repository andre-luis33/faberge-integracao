<?php

namespace App\Http\Controllers;

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
         if (Auth::check()) {
            $this->session = new SessionDto(
               session()->get('user.id'),
               session()->get('user.name'),
               session()->get('user.email'),
            );
         }

         return $next($request);
      });
   }
}
