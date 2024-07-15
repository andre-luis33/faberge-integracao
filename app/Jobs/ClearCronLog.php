<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClearCronLog implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public string $filePath = 'cron.log';

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
   public function handle(): void
   {
      unlink($this->filePath);
   }
}
