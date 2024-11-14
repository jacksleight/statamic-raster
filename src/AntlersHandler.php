<?php

namespace JackSleight\StatamicRaster;

use JackSleight\LaravelRaster\BaseHandler;
use Statamic\View\Antlers\Language\Nodes\AntlersNode;
use Statamic\View\Antlers\Language\Nodes\TagIdentifier;
use Statamic\View\Antlers\Language\Parser\DocumentParser;

class AntlersHandler extends BaseHandler
{
    public function hasFingerprint(): bool
    {
        $string = file_get_contents($this->raster->path());
        $nodes = app(DocumentParser::class)->parse($string);

        return collect($nodes)
            ->contains(function ($node) {
                return $node instanceof AntlersNode &&
                    $node->name instanceof TagIdentifier &&
                    $node->name->name === 'raster';
            });
    }

    /**
     * @param  array<mixed>  $args
     * @return array<mixed>
     */
    public function injectParams($params): array
    {
        if (! $this->raster->isAutomaticMode()) {
            return [];
        }

        $input = $this->raster->request()->all();
        $params = collect($input)
            ->merge($params)
            ->only([
                'width',
                'height',
                'basis',
                'scale',
                'type',
                'preview',
                'cache',
            ]);

        $params->each(fn ($value, $name) => $this->raster->{$name}($value));

        return $this->raster->data();
    }
}
