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
                            <h1><i class="fa fa-plus"></i></h1>
                            <p class="lead">New Track</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <button class="btn btn-success" type="submit">Create Track</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/jquery-2.1.4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    </body>
</html>
