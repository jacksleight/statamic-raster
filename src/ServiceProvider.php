<?php

namespace JackSleight\StatamicRaster;

use JackSleight\LaravelRaster\Raster;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\Raster::class,
    ];

    public function bootAddon()
    {
        Raster::handler('antlers.html', AntlersHandler::class);
        Raster::handler('antlers.php', AntlersHandler::class);
    }
}
