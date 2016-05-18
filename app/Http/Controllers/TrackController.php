<?php

namespace geolocation\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;

use geolocation\Http\Requests;
use geolocation\Http\Controllers\Controller;
use geolocation\Track;
use geolocation\Coordinate;
use geolocation\Stop;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tracks = Track::all();

        return view('list-tracks')
                ->with('tracks', $tracks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-track');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $track = Track::create([
                'name' => $request->get('name')
            ]);

        return redirect()->route('view_track', $track->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $track = Track::find($id);

        $coordinates = $track->coordinates()->orderBy('time', 'asc')->get();

        $index = 0;

        if ($coordinates->count() > 0) {
            $coords_array = [];

            foreach ($coordinates as $coord) {
                array_push($coords_array, [$coord->lat, $coord->lon]);
            }

            if (count($coords_array)%2 === 0) {
                $index = (count($coords_array)-1)/2;
            } else {
                $index = count($coords_array)/2;
            }

            return view('view-track')
                    ->with('track', $track)
                    ->with('coordinates', $coordinates)
                    ->with('map_coordinates', json_encode($coords_array))
                    ->with('map_center', $coords_array[$index]);
        } else {
            return view('view-track', compact('track'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function saveCoordinates(Request $request, $id)
    {
        $track = Track::find($id);

        if ($track) {
            $coords =  json_decode($request->get('coords'));

            foreach ($coords as $coord) {
                $datetime = new Carbon($coord[2]);

                $coordinate = new Coordinate([
                        'lat'   => $coord[0],
                        'lon'   => $coord[1],
                        'time'  => $datetime
                ]);

                $stop = $this->processCoordinate($coordinate);

                $coordinate->stop_distance = $stop[0];
                $coordinate->stop_id = $stop[1];

                $track->coordinates()->save($coordinate);
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function processCoordinate(Coordinate $coordinate)
    {
        $stops = Stop::all();
        $distance = null;
        $stop_id = null;

        foreach ($stops as $stop) {
            $calculated_distance = $this->vincentyGreatCircleDistance(
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

        return [$distance, $stop_id];
    }

    public static function vincentyGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo,
        $earthRadius = 6371000
    ) {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);

        return $angle * $earthRadius;
    }
}
