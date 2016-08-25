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

        if (!count($coordinates)) {
            $this->info('No coordinates found to be processed');
            exit;
        }

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
            $stop = DB::select(DB::raw('SELECT *, ROUND(6367 * ACOS(COS(RADIANS(lat)) * COS(RADIANS(:lat)) * COS(RADIANS(:lon) - RADIANS(lon)) + SIN(RADIANS(lat)) * SIN(RADIANS(:lat2))), 3) AS distance FROM stops ORDER BY distance ASC LIMIT 1'), ['lat' => $coordinate->lat, 'lon' => $coordinate->lon, 'lat2' => $coordinate->lat]);

            $coordinate->update([
                'stop_id' => $stop[0]->id,
                'stop_distance' => $stop[0]->distance,
                'processed' => 1
            ]);

            $bar->advance();
        }

        $bar->finish();
    }
}
