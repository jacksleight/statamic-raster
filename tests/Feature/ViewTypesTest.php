<?php

use JackSleight\LaravelRaster\Raster as LaravelRaster;
use JackSleight\StatamicRaster\Raster;

test('blade views cannot be rasterised by the addon', function () {
    expect(fn () => Raster::make('blade-probe'))
        ->toThrow(Exception::class, 'Cannot rasterise [blade-probe], Blade views are rendered by Laravel Raster, use its route and raster() helper instead.');
});

test('blade views cannot be rasterised through the addon route', function () {
    $this->withoutExceptionHandling();

    expect(fn () => $this->get('/!/raster/blade-probe'))
        ->toThrow(Exception::class, 'Blade views are rendered by Laravel Raster');
});

test('antlers views cannot be rasterised by laravel raster', function () {
    expect(fn () => LaravelRaster::make('probe'))
        ->toThrow(Exception::class, 'Antlers views are rendered by Statamic Raster');
});

test('antlers views can be rasterised by the addon', function () {
    expect(Raster::make('probe'))->toBeInstanceOf(Raster::class);
});
