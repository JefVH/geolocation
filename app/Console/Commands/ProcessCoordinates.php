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
        $this->info('|-----------------------------|');
        $this->info('|---- PROCESS COORDINATES ----|');
        $this->info('|-----------------------------|');

        $coordinates = Coordinate::notProcessed()->get();

        $coordinatesTable = [];
        foreach ($coordinates as $coordinate) {
            $coordinateArray = [];

            $coordinateArray['track'] = $coordinate->track->name;
            $coordinateArray['latitude'] = $coordinate->lat;
            $coordinateArray['longitude'] = $coordinate->lon;
            $coordinateArray['time'] = $coordinate->time;

            array_push($coordinatesTable, $coordinateArray);
        }

        $headers = ['Track', 'Latitude', 'Longitude', 'Time'];

        $this->table($headers, $coordinatesTable);

        $bar = $this->output->createProgressBar(count($coordinates));

        foreach ($coordinates as $coordinate) {
            $stop = DB::select('SELECT *, SQRT(POW(69.1 * (lat - :lat), 2) + POW(69.1 * (:lon - lon) + COS(lat / 57.3), 2)) AS distance FROM stops ORDER BY distance ASC LIMIT 1', ['lat' => $coordinate->lat, 'lon' => $coordinate->lon]);

            $coordinate->stop_id = $stop->id;
            $coordinate->stop_distance = $stop->distance;
            $coordinate->save();

            $bar->advance();
            $this->info('Coordinate ' . $coordinate->id . ' processed.');
        }

        $bar->finish();
    }
}
