<?php

namespace App\Providers;

use App\Services\CiliaService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void
   {
      $this->app->singleton(CiliaService::class, function ($app) {
         $mode = config('services.cilia.mode');
         return new CiliaService($mode);
      });
   }

   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
      //
   }
}
