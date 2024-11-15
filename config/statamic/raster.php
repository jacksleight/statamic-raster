<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    */

    'route' => '!/raster',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */

    'layout' => 'raster',

    /*
    |--------------------------------------------------------------------------
    | Sign URLs
    |--------------------------------------------------------------------------
    */

    'sign_urls' => env('RASTER_SIGN_URLS', false),

    /*
    |--------------------------------------------------------------------------
    | Cache Store
    |--------------------------------------------------------------------------
    */

    'cache_store' => env('RASTER_CACHE_STORE', config('cache.default')),

];
