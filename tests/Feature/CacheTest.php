<?php

use Illuminate\Support\Facades\Storage;
use JackSleight\LaravelRaster\Cache;

test('flushing a shared disk only removes the raster directory', function () {
    Storage::fake('shared');
    Storage::disk('shared')->put('other/keep.txt', 'keep');
    Storage::disk('shared')->put('raster/probe/_/image.png', 'image');
    config(['raster.cache.disk' => 'shared']);
    app()->forgetInstance(Cache::class);

    app(Cache::class)->flush();

    expect(Storage::disk('shared')->exists('other/keep.txt'))->toBeTrue()
        ->and(Storage::disk('shared')->exists('raster/probe'))->toBeFalse();
});
