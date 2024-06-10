<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return redirect('/login');
});


Route::get('/login', function () {
   return view('login');
})->name('login');


Route::prefix('/admin')->middleware('auth')->group(function () {

   Route::get('/inicio', function () {
      return view('admin.content.inicio');
   })->name('admin.inicio');

   Route::get('/prazo-entrega', function () {
      return view('admin.content.prazo-entrega');
   })->name('admin.prazo-entrega');

   Route::get('/grupos-de-peca', function () {
      return view('admin.content.grupos-de-peca');
   })->name('admin.grupos-de-peca');

   Route::get('/parametro', function () {
      return view('admin.content.parametro');
   })->name('admin.parametro');
});


Route::prefix('/api')->group(function () {

   Route::post('/login', [AuthController::class, 'login'])->name('api.login');
});
