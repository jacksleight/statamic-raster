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
        $layout = config('raster.layout') ?? 'raster';

        $data = [
            'raster' => $this->raster,
        ];

        $content = null;
        if ($this->raster->isAutomaticMode()) {
            $input = $this->raster->request()->all();
            $data = array_merge($data, $input['data'] ?? []);
            if (isset($data['content'])) {
                $content = Data::find($data['content']);
                unset($data['content']);
            }
        }

        $html = app(View::class)
            ->template($this->raster->name())
            ->layout($layout)
            ->cascadeContent($content)
            ->with($data)
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
                'basis',
                'scale',
                'type',
                'preview',
                'cache',
            ]);

        $params->each(fn ($value, $name) => $this->raster->{$name}($value));

        return $this->raster->data();
    }

    /**
     * @param  array<mixed>  $args
     * @return array<mixed>
     */
    public function resolveData(mixed $data, array $input): array
    {

        return $data;
    }
}
