<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use JackSleight\StatamicRaster\Raster;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Raster::browsershot(fn ($browsershot) => $browsershot
            ->setNodeModulePath(base_path('node_modules'))
        );

        if (Storage::exists('lab/signed')) {
            config([
                'statamic.raster.sign_urls' => true,
                'raster.sign_urls' => true,
            ]);
        }
    }
}
