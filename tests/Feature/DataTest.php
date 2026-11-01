<?php

use Illuminate\Support\Facades\Log;
use JackSleight\StatamicRaster\Raster;
use Statamic\View\View;

test('url data reaches the view', function () {
    expect(state($this->get('/!/raster/probe?data[label]=hi')))
        ->label->toBe('hi')
        ->data->toBe(['label' => 'hi']);
});

test('url tag data reaches the view', function () {
    $url = trim((new View)->template('url-tag-data')->with(['data' => ['label' => 'tagged']])->render());

    expect(state($this->get($url)))->label->toBe('tagged');
});

test('the main tag renders nothing', function () {
    Log::spy();

    $this->get('/!/raster/probe?data[label]=hi')->assertOk();

    Log::shouldNotHaveReceived('debug');
    Log::shouldNotHaveReceived('warning');
});

test('manual mode data reaches the view', function () {
    expect(Raster::make('manual')->width(100)->data(['label' => 'manual'])->preview(true)->render())
        ->toContain('"label":"manual"');
});
