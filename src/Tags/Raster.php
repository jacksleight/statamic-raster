<?php

namespace JackSleight\StatamicRaster\Tags;

use JackSleight\LaravelRaster\Raster as RasterObject;
use Statamic\Support\Str;
use Statamic\Tags\Tags;

class Raster extends Tags
{
    public function wildcard($src)
    {
        $this->params['src'] = $src;

        return $this->url();
    }

    public function index()
    {
        $raster = $this->context->get('raster');
        if (! $raster instanceof RasterObject) {
            return;
        }

        return $raster->injectParams(...$this->params->all());
    }

    public function url()
    {
        $name = Str::replace('/', '.', $this->params['src']);

        $data = $this->params->except('src')->all();

        return raster($name, $data)->toUrl();

    }
}
