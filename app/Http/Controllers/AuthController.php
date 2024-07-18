<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

   private User $user;

   public function __construct(User $user) {
      $this->user = $user;
   }

   public function login(Request $request)
   {
      $request->validate([
         'email' => 'required|string|exists:users,email',
         'password' => 'required|min:8'
      ]);

      $credentials = $request->only('email', 'password');
      $user = User::where('email', $credentials['email'])->first();

      if (!$user || !Hash::check($credentials['password'], $user->password)) {
         abort(400, 'Usuário/Senha inválidos');
      }

      /** @var Collection */
      $companies = $user->companies()
         ->select(['id', 'name', 'cnpj', 'primary'])
         ->where('active', true)
         ->get();

      $activeCompany = $companies->where('primary', true)->first();

      $request->session()->regenerate();

      $request->session()->put('user.id',    $user->id);
      $request->session()->put('user.name',  $user->name);
      $request->session()->put('user.email', $user->email);

      $request->session()->put('user.company.id',   $activeCompany->id);
      $request->session()->put('user.company.name', $activeCompany->name);
      $request->session()->put('user.company.cnpj', $activeCompany->cnpj);
      $request->session()->put('user.companies',    $companies->makeHidden('primary')->toArray());

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
