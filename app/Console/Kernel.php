<?php

namespace App\Console;

use App\Console\Commands\ApplyInterestCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ApplyInterestCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('interest:apply')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
