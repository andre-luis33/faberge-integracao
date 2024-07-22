<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Log;

class ProfileController extends Controller
{

   private User $user;

   public function __construct(User $user) {
      $this->user = $user;
      $this->setSession();
   }


   public function index() {
      $user = $this->user->find($this->session->userId, ['id', 'email']);
      return response()->json($user, 200);
   }


   public function update(Request $request) {
      $userId = $this->session->userId;

      $validation = [
         'email'    => 'required|string|max:100|email:filter',
         'password' => [
            'nullable',
            Password::min(6)
               ->max(20)
               ->letters()
               ->numbers()
               ->symbols()
               ->mixedCase()
               ->uncompromised()
         ]
      ];

      $request->validate($validation);

      $email = $request->input('email');
      $password = $request->input('password');

      $emailExists = $this->user
         ->where([
            'email' => $email,
            ['id', '!=', $userId]
         ])
         ->exists();

      if($emailExists)
         return abort(400, 'O e-mail informado já existe');

      $data = [
         'email' => $email
      ];

      if($password)
         $data['password'] = Hash::make($password);

      try {

         $this->user
            ->where('id', $userId)
            ->update($data);

         return response(status: 204);

      } catch (Exception $e) {
         Log::error($e->getMessage());
         abort(500, 'Erro interno no servidor');
      }
   }

}
