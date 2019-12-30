let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'public/theme/gauk/assets/css/bootstrap.css',
    'public/theme/gauk/assets/css/bootstrap-theme.css',
    'public/theme/gauk/assets/css/iconmoon.css',
    'public/theme/4/assets/css/icons/font-awesome/css/font-awesome.min.css',
    'public/assets/css/icons/fontawesome5/css/fontawesome-all.min.css',
    'public/theme/gauk/assets/css/chosen.css',
    'public/theme/gauk/assets/css/style.css',
    'public/theme/gauk/assets/css/cs-automobile-plugin.css',
    'public/theme/gauk/assets/css/color.css',
    'public/theme/gauk/assets/css/widget.css',
    'public/theme/gauk/assets/css/responsive.css',
    'public/assets/css/gsi-step-indicator.css',
    'public/assets/css/core.css',
    'public/assets/css/ouibounce.min.css',
    'public/assets/css/public.css',
    'public/theme/gauk/assets/css/register.css',
    'public/assets/css/vehicles.css'
], 'public/assets/css/gauk.css').version();


mix.scripts([
    'public/theme/gauk/assets/scripts/jquery.js',
    'public/theme/gauk/assets/scripts/modernizr.js',
    'public/theme/gauk/assets/scripts/bootstrap.min.js',
    'public/theme/gauk/assets/scripts/responsive.menu.js',
    'public/theme/gauk/assets/scripts/chosen.select.js',
    'public/theme/gauk/assets/scripts/slick.js',
    'public/theme/gauk/assets/scripts/echo.js',
    'public/theme/gauk/assets/scripts/functions.js',
    'public/assets/js/NavTabs.js'
], 'public/assets/js/gauk.js').version();
