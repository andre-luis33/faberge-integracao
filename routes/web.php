<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeliveryTimeController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\IntegrationSettingsController;
use App\Http\Controllers\PartGroupController;
use App\Http\Controllers\SessionController;
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

   Route::get('/empresas', function () {
      return view('admin.content.empresas');
   })->name('admin.empresas');

   Route::get('/integracao', function () {
      return view('admin.content.integracao');
   })->name('admin.integracao');

   Route::get('/integrations/executions/{id}/csv', [IntegrationController::class, 'downloadCsv'])->name('admin.integracao.csv-download');
});


Route::prefix('/api')->group(function () {

   Route::post('/login', [AuthController::class, 'login'])->name('api.login');

   Route::middleware('auth')->group(function() {

      Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

      Route::prefix('/session')->group(function() {
         Route::get('/', [SessionController::class, 'index']);
         Route::put('/sidebar', [SessionController::class, 'sidebar']);
         Route::put('/active-company/{id}', [SessionController::class, 'activeCompany']);
      });

      Route::prefix('/companies')->group(function() {
         Route::get('/',  [CompanyController::class, 'index']);
         Route::post('/', [CompanyController::class,  'store']);
         Route::put('/{id}', [CompanyController::class,  'update']);
      });

      Route::prefix('/part-groups')->group(function() {
         Route::get('/', [PartGroupController::class, 'index']);
         Route::put('/', [PartGroupController::class, 'update']);
      });

      Route::prefix('/delivery-times')->group(function() {
         Route::get('/', [DeliveryTimeController::class, 'index']);
         Route::put('/', [DeliveryTimeController::class, 'update']);
      });

      Route::prefix('/integration-settings')->group(function() {
         Route::get('/', [IntegrationSettingsController::class, 'index']);
         Route::put('/', [IntegrationSettingsController::class, 'update']);
      });

      Route::prefix('/integrations/executions')->group(function() {
         Route::get('/', [IntegrationController::class, 'index']);
         Route::post('/', [IntegrationController::class, 'store']);
      });

   });
});
