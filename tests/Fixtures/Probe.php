<?php

namespace Tests\Fixtures;

use Statamic\Tags\Tags;

class Probe extends Tags
{
    public function index()
    {
        $raster = $this->context->get('raster');

        return '<script id="state" type="application/json">'.json_encode([
            'width' => $raster->width(),
            'transparent' => $raster->transparent(),
            'cache' => $raster->cache(),
            'cacheId' => $raster->cacheId(),
            'data' => $raster->data(),
            'content' => $raster->content()?->id(),
            'label' => $this->context->value('label'),
        ]).'</script>';
    }
}
