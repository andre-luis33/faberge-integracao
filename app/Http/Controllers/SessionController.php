<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
   public function __construct() {
      $this->setSession();
   }


   public function sidebar(Request $request) {
      $closed = (bool) $request->input('closed');
      session()->put('user.sidebar.closed', $closed);
      return response(status: 204);
   }
}
