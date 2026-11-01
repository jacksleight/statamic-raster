<?php

namespace JackSleight\StatamicRaster;

use Exception;
use Facades\Statamic\CP\LivePreview;
use Illuminate\Http\Response;
use JackSleight\LaravelRaster\Raster as LaravelRaster;
use JackSleight\StatamicRaster\Http\Responses\DataResponse as DataResponse;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Data;
use Statamic\View\View;

class Raster extends LaravelRaster
{
    protected ?object $content = null;

    protected static $extensions = [
        'antlers.html' => AntlersHandler::class,
        'antlers.php' => AntlersHandler::class,
    ];

    protected $route = 'statamic-raster.render';

    protected function unsupportedView(string $extension): Exception
    {
        return match ($extension) {
            'blade.php' => new Exception("Cannot rasterise [{$this->name}], Blade views are rendered by Laravel Raster, use its route and raster() helper instead."),
            default => parent::unsupportedView($extension),
        };
    }

    public function content(object|string|null $content = null): object|null
    {
        if (func_num_args() > 0) {
            if (is_string($content) && ! $content = Data::find($content)) {
                throw new NotFoundHttpException;
            }

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
            ->cascadeContent(isset($this->content) ? $this->content : null)
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
        $this->data($this->requestParams()['data'] ?? []);

        if (! $this->request->content && ! $this->request->isLivePreview()) {
            return;
        }

        $token = $this->request->statamicToken();
        if ($token) {
            $content = LivePreview::item($token);
            if (! $content) {
                throw new NotFoundHttpException;
            }
            $this->content = $content;
            $this->preview = true;

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
