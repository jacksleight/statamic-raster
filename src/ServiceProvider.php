<?php

namespace JackSleight\StatamicRaster;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\Raster::class,
    ];

    public function bootAddon()
    {
        $this->publishes([
            __DIR__.'/../config/statamic/raster.php' => config_path('statamic/raster.php'),
        ], 'statamic-raster-config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/statamic/raster.php', 'statamic.raster'
        );

        config()->set('raster.sign_urls', config('statamic.raster.sign_urls'));
        config()->set('raster.cache', config('statamic.raster.cache'));
    }
}
