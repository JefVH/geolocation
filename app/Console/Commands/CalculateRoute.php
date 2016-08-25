<?php

namespace geolocation\Console\Commands;

use Carbon\Carbon;
use geolocation\Coordinate;
use geolocation\Stop;
use geolocation\StopTime;
use geolocation\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CalculateRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:calculate {track} {--diff=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate a route';

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
        $this->info('|------ Calculate Route ------|');
        $this->info('|-----------------------------|');

        $trackId = $this->argument('track');
        $timeDiff = $this->option('diff');

        $stops = [];

        $coordinates = DB::select(
            DB::raw('SELECT t1.* FROM coordinates t1 LEFT JOIN coordinates t2 ON t1.stop_id = t2.stop_id AND t1.stop_distance > t2.stop_distance WHERE t2.stop_distance IS NULL AND t1.track_id = :trackId order by t1.time asc'),
            [
                'trackId' => $trackId
            ]
        );

        if (!count($coordinates)) {
            $this->error('No coordinates were found for your track');
            exit;
        }

        foreach ($coordinates as $coordinate) {
            $time = Carbon::parse($coordinate->time)->toTimeString();

            if (array_key_exists($coordinate->stop_id, $stops)) {
                if ($coordinate->time < $stops[$coordinate->stop_id]['start']) {
                    $stops[$coordinate->stop_id]['start'] = $time;
                }

                if ($time > $stops[$coordinate->stop_id]['end']) {
                    $stops[$coordinate->stop_id]['end'] = $time;
                }
            } else {
                $stops[$coordinate->stop_id]['start'] = $time;
                $stops[$coordinate->stop_id]['end'] = $time;
            }
        }

        $trips = [];

        foreach ($stops as $id => $times) {
            $stopTimes = StopTime::where('stop_id', $id)->get();
            $start = Carbon::parse($times['start']);
            $end = Carbon::parse($times['end']);

            foreach ($stopTimes as $stopTime) {
                $arrivalTime = Carbon::parse($stopTime->arrival_time);
                $departureTime = Carbon::parse($stopTime->departure_time);

                if (($start->diffInMinutes($arrivalTime) < $timeDiff) && $end->diffInMinutes($departureTime) < $timeDiff) {
                    array_push($trips, $stopTime->trip_id);
                }
            }
        }

        if (!count($trips)) {
            $this->error('No possible trips were found for your time difference');
            exit;
        }

        $count = array_count_values($trips);
        $tripId = array_search(max($count), $count);

        reset($stops);
        $startStopId = key($stops);
        end($stops);
        $endStopId = key($stops);

        $trip = Trip::find($tripId);
        $startStop = Stop::find($startStopId);
        $endStop = Stop::find($endStopId);


        $this->info('You probably were on the trip ' . $trip->headsign);
        $this->info('You probably got on at stop ' . $startStop->name);
        $this->info('You probably got off at stop ' . $endStop->name);

    }
}
