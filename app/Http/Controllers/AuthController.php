<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'cnpj' => 'required|string|exists:users,cnpj',
            'password' => 'required|min:8'
        ]);

        $credentials = $request->only('cnpj', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'error' => 'Usuário não encontrado'
            ])->withInput();
        }

        $user = Auth::user();

        $request->session()->regenerate();
        $request->session()->put('user.id', $user->id);
        $request->session()->put('user.name', $user->name);
        $request->session()->put('user.cnpj', $user->cnpj);

        // return response()->json(['message' => 'Sucesso'], 200);
        return redirect()->route('admin.inicio');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response(status: 204);
    }
}
