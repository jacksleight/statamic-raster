<?php

use JackSleight\StatamicRaster\Raster;

test('unknown query parameters are ignored', function () {
    expect(state($this->get('/!/raster/probe?foo=1&utm_source=x')))
        ->width->toBe(100);
});

test('signed urls are accepted when signing is enabled', function () {
    config(['raster.sign_urls' => true]);

    expect(state($this->get(Raster::make('probe')->width(300)->toUrl())))
        ->width->toBe(300);
});

test('unsigned urls are rejected when signing is enabled', function () {
    config(['raster.sign_urls' => true]);

    $this->get('/!/raster/probe')->assertUnauthorized();
});

test('url options are applied', function () {
    expect(state($this->get('/!/raster/probe?width=300&transparent=1&cache=1')))
        ->width->toBe(300)
        ->transparent->toBeTrue()
        ->cache->toBeTrue();
});

test('boolean url options accept false', function () {
    expect(state($this->get('/!/raster/probe?transparent=false&cache=false')))
        ->transparent->toBeFalse()
        ->cache->toBeFalse();
});

test('cache id cannot be set from the url', function () {
    expect(state($this->get('/!/raster/probe?cacheId=evil&cache_id=evil')))
        ->cacheId->toBe('_');
});
