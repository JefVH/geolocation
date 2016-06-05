<?php

namespace geolocation\Console\Commands;

use geolocation\Coordinate;
use geolocation\Stop;
use Illuminate\Console\Command;

class ProcessCoordinates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coordinates:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the coordinates and find the nearest stop';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coordinates = Coordinate::notProcessed()->get();
        $stops = Stop::all();

        foreach ($coordinates as $coordinate) {
            $distance = null;
            $stop_id = null;

            foreach ($stops as $stop) {
                $calculated_distance = vincentyGreatCircleDistance(
                    $coordinate->lat,
                    $coordinate->lon,
                    $stop->lat,
                    $stop->lon
                );

                if ($distance == null) {
                    $distance = $calculated_distance;
                    $stop_id = $stop->id;
                } elseif ($calculated_distance < $distance) {
                    $distance = $calculated_distance;
                    $stop_id = $stop->id;
                }
            }

            $coordinate->stop_id = $stop_id;
            $coordinate->stop_distance = $distance;
            $coordinate->save();
        }
    }
}
