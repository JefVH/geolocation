<?php

namespace geolocation\Http\Controllers;

use geolocation\Trip;
use Illuminate\Http\Request;

use Carbon\Carbon;

use geolocation\Http\Requests;
use geolocation\Http\Controllers\Controller;
use geolocation\Track;
use geolocation\Coordinate;
use geolocation\Stop;
use Illuminate\Support\Facades\DB;

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

        $trip = null;
        $startStop = null;
        $endStop = null;

        if ($track->processed) {
            $trip = Trip::find($track->trip_id);
            $startStop = Stop::find($track->start_stop_id);
            $endStop = Stop::find($track->end_stop_id);
        }

        $coordinates = $track->coordinates()->orderBy('time', 'asc')->get();
        $coordinatesFiltered = DB::table('coordinates')
            ->select('lat', 'lon')
            ->where('track_id', $track->id)
            ->orderBy('time', 'asc')
            ->get();

        $index = 0;

        if (count($coordinatesFiltered) > 0) {
            $coords_array = [];

            foreach ($coordinatesFiltered as $coord) {
                array_push($coords_array, $coord);
            }

            if (count($coords_array)%2 === 0) {
                $index = (count($coords_array)-1)/2;
            } else {
                $index = count($coords_array)/2;
            }

            $mapCoordinatesJsonRaw = json_encode($coords_array);
            $mapCoordinatesJson = preg_replace('/"([^"]+)"\s*:\s*/', '$1:', $mapCoordinatesJsonRaw);

            return view('view-track')
                    ->with('track', $track)
                    ->with('trip', $trip)
                    ->with('startStop', $startStop)
                    ->with('endStop', $endStop)
                    ->with('coordinates', $coordinates)
                    ->with('map_coordinates', $mapCoordinatesJson)
                    ->with('map_center', $coords_array[$index]);
        } else {
            return view('view-track')
                ->with('track', $track)
                ->with('trip', $trip)
                ->with('startStop', $startStop)
                ->with('endStop', $endStop);
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

    public function saveCoordinate(Request $request, $id)
    {
        $track = Track::find($id);

        if ($track) {
            $coord =  json_decode($request->get('coordinate'));

            $date = Carbon::parse($coord[2]);

            $coordinate = new Coordinate([
                    'lat'   => $coord[0],
                    'lon'   => $coord[1],
                    'time'  => $date->toDateTimeString()
            ]);

            $track->coordinates()->save($coordinate);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
