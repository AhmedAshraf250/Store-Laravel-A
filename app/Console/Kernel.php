<?php

namespace App\Console;

use App\Jobs\DeleteExpireOrders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
        /*
            > php artisan orders:delete-expired
                ↓
            artisan (file)
                ↓
            Console Kernel (App\Console\Kernel)
                ↓
            Command class
                ↓
            handle()
        */

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // > php artisan schedule:work 

        // $schedule->command('inspire')->hourly();
        $schedule->job(new DeleteExpireOrders)->everyMinute();
        $schedule->command('orders:delete-expired')->daily(); //->withoutOverlapping()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
