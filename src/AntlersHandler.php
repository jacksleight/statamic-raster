<?php

namespace JackSleight\StatamicRaster;

use JackSleight\LaravelRaster\BaseHandler;
use Statamic\Facades\Data;
use Statamic\View\Antlers\Language\Nodes\AntlersNode;
use Statamic\View\Antlers\Language\Nodes\TagIdentifier;
use Statamic\View\Antlers\Language\Parser\DocumentParser;
use Statamic\View\View;

class AntlersHandler extends BaseHandler
{
    /**
     * @param  array<mixed>  $data
     */
    public function renderHtml(): string
    {
        $layout = 'layout.raster';
        // $layout = config('raster.layout');

        $html = app(View::class)
            ->template($this->raster->name())
            ->layout($layout)
            ->with([
                'raster' => $this->raster,
            ])
            ->render();

        return $html;
    }

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
    public function resolveData(mixed $data, array $input): array
    {
        if (isset($data['content'])) {
            $content = Data::find($data['content']);
            if ($content) {
                $data = array_merge($data, $content->toAugmentedArray());
            }
        }

        return $data;
    }
}
