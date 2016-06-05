<?php

namespace geolocation\Console\Commands;

use geolocation\Coordinate;
use geolocation\Stop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        foreach ($coordinates as $coordinate) {
            $stop = DB::select('SELECT *, SQRT(POW(69.1 * (lat - :lat), 2) + POW(69.1 * (:lon - lon) + COS(lat / 57.3), 2)) AS distance FROM stops ORDER BY distance ASC LIMIT 1', ['lat' => $coordinate->lat, 'lon' => $coordinate->lon]);

            $coordinate->stop_id = $stop->id;
            $coordinate->stop_distance = $stop->distance;
            $coordinate->save();
        }
    }
}
