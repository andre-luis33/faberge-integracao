<?php

namespace App\Jobs;

use App\Business\StockUpdateBusiness;
use App\Models\DeliveryTime;
use App\Models\IntegrationSettings;
use App\Models\PartGroup;
use App\Services\CiliaService;
use App\Services\LinxService;
use App\Utils\Masks;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StockUpdateJob implements ShouldQueue
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
   public function handle(IntegrationSettings $integrationSettings, StockUpdateBusiness $stockUpdateBusiness): void
   {

      $companiesSettings = $integrationSettings->findAvailableIntegrations();

      $companiesSettings->each(function(IntegrationSettings $integrationSettings) use ($stockUpdateBusiness) {
         $companyId = $integrationSettings->company_id;
         $stockUpdateBusiness->updateStock($companyId);
      });


   }
}
