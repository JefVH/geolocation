<?php

namespace geolocation\Console;

use geolocation\Console\Commands\CalculateRoute;
use geolocation\Console\Commands\ProcessCoordinates;
use geolocation\Console\Commands\ResetCoordinates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ProcessCoordinates::class,
        CalculateRoute::class,
        ResetCoordinates::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('coordinates:process')->everyFiveMinutes()->withoutOverlapping();
    }
}
