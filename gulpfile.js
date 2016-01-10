var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.copy('bower_components/jQuery-Geolocation/jquery.geolocation.min.js', 'public/js/jquery.geolocation.min.js');
    mix.copy('bower_components/maplace-js/dist/maplace.min.js', 'public/js/maplace.min.js');
});
