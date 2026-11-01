<?php

namespace Tests;

use JackSleight\LaravelRaster\ServiceProvider as LaravelRasterServiceProvider;
use JackSleight\StatamicRaster\ServiceProvider;
use Statamic\Testing\AddonTestCase;
use Statamic\Testing\Concerns\PreventsSavingStacheItemsToDisk;
use Tests\Fixtures\Probe;

class TestCase extends AddonTestCase
{
    use PreventsSavingStacheItemsToDisk;

    protected string $addonServiceProvider = ServiceProvider::class;

    protected function setUp(): void
    {
        parent::setUp();

        Probe::register();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelRasterServiceProvider::class,
            ...parent::getPackageProviders($app),
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('view.paths', [__DIR__.'/__fixtures__/views']);
    }
}
