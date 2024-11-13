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

        $params = $this->params->only([
            'data',
            'width',
            'basis',
            'scale',
            'type',
            'preview',
        ])->all();

        if (! isset($params['data']['content'])) {
            $params['data']['content'] = $this->context->get('id')?->value();
        }

        $raster = raster($name);
        collect($params)->each(fn ($value, $name) => $raster->{$name}($value));

        return $raster->toUrl();

    }
}
