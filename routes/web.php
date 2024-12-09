<?php

use Illuminate\Http\Request;
use JackSleight\StatamicRaster\Raster;

Route::group(['as' => 'statamic-raster.'], function () {
    $route = config('statamic.raster.route');
    Route::get($route.'/{name}', function (Request $request) {
        if (config('raster.sign_urls') && ! $request->hasValidSignature() && ! $request->isLivePreview()) {
            abort(401);
        }

        return Raster::makeFromRequest($request);
    })->name('render');
});
