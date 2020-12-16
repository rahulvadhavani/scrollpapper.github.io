process.env.DISABLE_NOTIFIER = true;
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

elixir(function (mix) {
    mix.scripts([
            "sb-admin-2.js",
        ], './public/assets/js/admin_app.min.js')  
        .styles([
            "sb-admin-2.css",
        ], './public/assets/css/admin_app.min.css')
        mix.scripts([
            "paginate.js",
        ], './public/assets/js/paginate.min.js')  
        
        
});

