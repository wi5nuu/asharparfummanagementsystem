<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Daily backup at 2 AM
        $schedule->command('backup:run')->dailyAt('02:00');
        
        // Check for low stock alerts every hour
        $schedule->call(function () {
            \App\Jobs\CheckLowStockJob::dispatch();
        })->hourly();
        
        // Check for expiring products daily
        $schedule->call(function () {
            \App\Jobs\CheckExpiringProductsJob::dispatch();
        })->daily();
        
        // Generate daily sales report at midnight
        $schedule->call(function () {
            \App\Jobs\GenerateDailyReportJob::dispatch();
        })->dailyAt('23:59');
    }
    
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}