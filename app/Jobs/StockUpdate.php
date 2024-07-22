<?php

namespace App\Jobs;

use App\Business\IntegrationBusiness;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StockUpdate implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /**
    * Create a new job instance.
    */
   public function __construct()
   {
      //
   }

   /**
    * Execute the job.
    */
   public function handle(IntegrationBusiness $integrationBusiness): void
   {
      set_time_limit(500);
      ini_set('max_execution_time', 500);

      Log::channel('integration')->info('Integration job started #####');
      $integrations = $integrationBusiness->findIntegrationsToRun();

      if($integrations->count() < 1) {
         Log::channel('integration')->info('No integrations to run');
         return;
      }

      $companiesIds = $integrations->pluck('company_id')->toArray();

      $companiesIdsStr = implode(', ', $companiesIds);
      $integrationsCount = $integrations->count();
      Log::channel('integration')->info("Integrations running! We have {$integrationsCount} to run, for the following companies ids: {$companiesIdsStr}");

      foreach($companiesIds as $companyId) {

         try {
            Log::channel('integration')->info("Running integration for company {$companyId}");
            $integrationBusiness->executeIntegration($companyId, false);
            Log::channel('integration')->info("Integration for {$companyId} completed successfully!");
         } catch (Exception $e) {
            Log::channel('integration')->emergency("Error running integration for company {$companyId}! Error: ".$e->getMessage());
         }

      }

   }
}
