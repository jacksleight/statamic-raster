<?php

namespace JackSleight\StatamicRaster\Tags;

use JackSleight\StatamicRaster\Raster as RasterCore;
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
        if (! $raster instanceof RasterCore) {
            return;
        }

        $params = $this->params
            ->mapWithKeys(fn ($value, $name) => [Str::camel($name) => $value])
            ->all();

        return $raster->handler()->injectParams($params);
    }

    public function url()
    {
        $name = Str::replace('/', '.', $this->params['src']);

        $params = $this->params;

        if (! isset($params['content'])) {
            $params['content'] = $this->context->get('page');
        }

        $raster = RasterCore::make($name);
        collect($params)->each(fn ($value, $name) => $raster->{$name}($value));

        return $raster->toUrl();
    }
}
