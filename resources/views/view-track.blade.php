    <!DOCTYPE html>
<html>
    <head>
        <title>Geolocation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    </head>
    <body>
        <form>
            <input type="hidden" name="track_id" id="track_id" value="{{ $track->id }}">
        </form>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><i class="fa fa-location-arrow"></i></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
                    <li><a href="{{ url() }}"><i class="fa fa-home"></i>&nbsp;Home <span class="sr-only"></span></a></li>
                    <li class="active"><a href="#"><i class="fa fa-location-arrow"></i>&nbsp;Track</a></li>
                    <li><a href="{{ route('tracks') }}"><i class="fa fa-history"></i>&nbsp;History</a></li>
                    <li><a href="{{ route('public_transport') }}"><i class="fa fa-bus"></i>&nbsp;Public Transport</a></li>
                  </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container -->
        </div>
        <div class="container">
                <div class="page-header" id="banner">
                    <div class="row">
                        <div class="col-md-12">
                            <h1><i class="fa fa-location-arrow"></i></h1>
                            <p class="lead">{{ $track->name }}</p>
                            <btn class="btn btn-success btn-lg" id="start-tracking"><i class="fa fa-play"></i>&nbsp;Start Tracking</btn>
                            <btn class="btn btn-danger btn-lg" id="stop-tracking" style="display: none;"><i class="fa fa-stop"></i>&nbsp;Stop Tracking</btn>
                            <btn class="btn btn-primary btn-lg" id="saving" style="display: none;"><i class="fa fa-spinner fa-spin"></i>&nbsp;Saving</btn>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Information</h3>
                        <label>Name</label>
                        <p>{{ $track->name }}</p>
                        <label>Created</label>
                        <p>{{ $track->created_at }}</p>
                        <label>Last Updated</label>
                        <p>{{ $track->updated_at }}</p>
                    </div>
                </div>
                @if(isset($coordinates))
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Coordinates</h3>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coordinates as $coordinate)
                                        <tr>
                                            <td>{{ $coordinate->lat }}</td>
                                            <td>{{ $coordinate->lon }}</td>
                                            <td>{{ $coordinate->time }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Map</h3>
                            <div class="col-md-2">
                                <div id="gmap_controls"></div>
                            </div>
                            <div class="col-md-10">
                                <div id="map" style="width: 100%; height: 600px;"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/jquery.geolocation.min.js') }}"></script>

        <script>
            var track_coords = new Array();
            var tracker; 

            $('#start-tracking').on('click', function() {
                $(this).hide();
                $('#stop-tracking').show();
                tracker = $.geolocation.watch({win: addCoordinate, settings: {enableHighAccuracy: true}, fail: trackingFail});
            });

            $('#stop-tracking').on('click', function() {
                $(this).hide();
                $('#saving').show();
                $.geolocation.stop(tracker);
                saveCoordinates();
            });

            function addCoordinate(position)
            {
                var date = new Date(position.timestamp);
                var coordinate = [position.coords.latitude, position.coords.longitude, date.toString()];
                track_coords.push(coordinate);
            };

            function trackingFail()
            {
                alert('Location Tracking is not possible');
            };

            function saveCoordinates() {
                var track_coords_json = JSON.stringify(track_coords);

                var post_url = "coordinates";

                $.post( post_url, { coords: track_coords_json }, function(data)
                {
                    if(data.success)
                    {
                        $('#saving').hide();
                        $('#start-tracking').show();
                        location.reload(true);
                    }
                    else
                    {
                        $('#start-tracking').show();
                        alert('There was an error saving the tracking data');
                    }
                });
            };
        </script>
        @if(isset($coordinates))
            <script src="//maps.google.com/maps/api/js?libraries=geometry&v=3.22&key=AIzaSyDO1gmbrToFXG9hV3GS4UoT_V-03Ks29pc"></script>
            <script src="{{ asset('js/maplace.min.js') }}"></script>
            <script>
                new Maplace({
                    locations: {{ $map_coordinates }},
                    map_div: '#map',
                    controls_div: '#gmap_controls',
                    controls_type: 'list',
                    controls_on_map: false,
                    view_all_text: 'All Coordinates',
                    type: 'polyline'
                }).Load();
            </script>
        @endif
    </body>
</html>
