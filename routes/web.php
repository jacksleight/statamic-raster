<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use JackSleight\StatamicRaster\Raster;

Route::group(['as' => 'statamic-raster.'], function () {
    $route = config('statamic.raster.route');
    Route::get($route.'/{name}', function (Request $request) {
        if (config('raster.sign_urls') && ! $request->hasValidSignature() && ! $request->isLivePreview()) {
            abort(401);
        }

        abort_unless(View::exists($request->route('name')), 404);

        return Raster::makeFromRequest($request);
    })->name('render');
});
