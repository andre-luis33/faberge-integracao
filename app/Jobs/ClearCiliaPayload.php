<?php

namespace App\Jobs;

use App\Models\IntegrationExecution;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Str;

/**
 * Everytime the job runs, we save the csv payload sent to cilia. With time, for many companies, this can get really heavy
 */
class ClearCiliaPayload implements ShouldQueue
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
   public function handle(IntegrationExecution $integrationExecution): void
   {
      ini_set('memory_limit', '512M');
      set_time_limit(600);
      ini_set('max_execution_time', 600);

      $date = date('Y-m-d', strtotime('-7 days'));

      $executionsPerQuery = 10;
      $updatedRows = 0;
      $fetchedRows = 0;
      $idsWithError = collect();
      $lastId = 0;

      while (true) {
         $executions = $integrationExecution
            ->select(['id', 'cilia_payload'])
            ->where('cilia_status_code', '!=', null)
            ->where('is_processed', false)
            ->where('created_at', '<=', $date)
            ->where('id', '>', $lastId)
            ->orderBy('id')
            ->limit($executionsPerQuery)
            ->get();

         if ($executions->isEmpty()) {
            break;
         }

         $fetchedRows+=$executions->count();

         // Process each record
         $executions->each(function ($execution) use ($integrationExecution, &$updatedRows, &$idsWithError, &$lastId) {
            $lines = explode("\n", $execution->cilia_payload);
            $lines = array_filter($lines);

            $rowsCount = count($lines) - 1; // exclude header

            try {
               $integrationExecution
                  ->where('id', $execution->id)
                  ->update([
                     'cilia_payload' => "Cilia Payload sent {$rowsCount} items",
                     'is_processed' => true,
                  ]);

               $updatedRows++;
               echo $updatedRows . ' Rows updated, current id: '.$execution->id . PHP_EOL;

            } catch (\Exception $e) {
               $idsWithError->push($execution->id);

            } finally {
               $lastId = $execution->id; // Update the last processed ID
            }
         });
      }


      $idsMessage = $idsWithError->count() ? "| Ids with error:" . $idsWithError->join(',') : '';
      $logMessage = "Updated rows: {$updatedRows}/{$fetchedRows} {$idsMessage}";
      Log::channel('clear_cilia_payload')->info($logMessage);

   }
}
