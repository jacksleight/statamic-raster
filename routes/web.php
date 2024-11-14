<?php

use Illuminate\Http\Request;
use JackSleight\StatamicRaster\Raster;

Route::group(['as' => 'statamic-raster.'], function () {
    $route = config('statamic.raster.route');
    Route::get($route.'/{name}', function (Request $request, $name) {
        if (config('raster.sign_urls') && ! $request->hasValidSignature()) {
            abort(401);
        }

        return new Raster($name, request: $request);
    })->name('render');
});
