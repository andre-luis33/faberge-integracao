<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login(Request $request)
   {
      $request->validate([
         'email' => 'required|string|exists:users,email',
         'password' => 'required|min:8'
      ]);

      $credentials = $request->only('email', 'password');

      if (!Auth::attempt($credentials)) {
         abort(400, 'Usuário/Senha inválidos');
      }

      $user = Auth::user();

      $request->session()->regenerate();
      $request->session()->put('user.id', $user->id);
      $request->session()->put('user.name', $user->name);
      $request->session()->put('user.email', $user->email);
      $request->session()->put('user.sidebar.closed', false);

      return response()->json(['message' => 'Sucesso'], 200);
   }

   public function logout(Request $request)
   {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return response(status: 204);
   }
}
