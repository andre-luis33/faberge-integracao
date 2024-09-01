<?php

namespace App\Console;

use App\Jobs\ClearCiliaPayload;
use App\Jobs\ClearCronLog;
use App\Jobs\StockUpdate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   /**
    * Define the application's command schedule.
    */
   protected function schedule(Schedule $schedule): void
   {
      $schedule->job(new StockUpdate)
         ->everyMinute()
         ->weekdays()
         ->between('7:00', '23:00')
         ->withoutOverlapping(600);

      $schedule->job(new ClearCronLog)
         ->dailyAt('00:00');

      $schedule->job(new ClearCiliaPayload)
         ->saturdays()
         ->at('00:00');

      $schedule->job(new ClearCiliaPayload)
         ->sundays()
         ->at('00:00');
   }

   /**
    * Register the commands for the application.
    */
   protected function commands(): void
   {
      $this->load(__DIR__ . '/Commands');

      require base_path('routes/console.php');
   }
}
