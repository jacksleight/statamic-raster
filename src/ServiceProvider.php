<?php

namespace JackSleight\StatamicRaster;

use JackSleight\LaravelRaster\Raster;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        Fieldtypes\Raster::class,
    ];

    protected $tags = [
        Tags\Raster::class,
    ];

    protected $vite = [
        'hotFile' => __DIR__.'/../vite.hot',
        'publicDirectory' => 'dist',
        'input' => [
            'resources/js/addon.js',
        ],
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
        config()->set('raster.cache_store', config('statamic.raster.cache_store'));

        Raster::extension('antlers.html', AntlersHandler::class);
        Raster::extension('antlers.php', AntlersHandler::class);
    }
}
