<?php

namespace JackSleight\StatamicRaster;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JackSleight\LaravelRaster\Raster as LaravelRaster;
use JackSleight\StatamicRaster\Http\Responses\DataResponse as DataResponse;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Data;
use Statamic\View\View;

class Raster extends LaravelRaster
{
    protected ?object $content;

    protected $route = 'statamic-raster.render';

    public function __construct(string $name, ?object $content = null, array $data = [], ?Request $request = null)
    {
        parent::__construct($name, $data, $request);

        $this->content = $content;
    }

    public function content(?object $content = null): object
    {
        if (func_num_args() > 0) {
            $this->content = $content;

            return $this;
        }

        return $this->content;
    }

    protected function renderHtml(): string
    {
        $layout = config('statamic.raster.layout');

        $html = (new View)
            ->template($this->name())
            ->layout($layout)
            ->cascadeContent($this->content)
            ->with([
                ...$this->data,
                'raster' => $this,
            ])
            ->render();

        return $html;
    }

    public function toResponse($request): Response
    {
        if ($this->isAutomaticMode()) {
            $this->handleRequest();
        }

        return parent::toResponse($request);
    }

    protected function handleRequest(): void
    {
        if (! $this->request->content) {
            return;
        }

        $content = Data::find($this->request->content);
        if (! $content) {
            throw new NotFoundHttpException;
        }

        (new DataResponse($content))->verifyResponse($this->request);
        $this->content = $content;
    }

    protected function gatherParams(): array
    {
        return [
            'content' => $this->content?->id(),
            ...parent::gatherParams(),
        ];
    }
}
