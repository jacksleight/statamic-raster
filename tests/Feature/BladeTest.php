<?php

test('blade views render through laravel raster', function () {
    $url = (string) raster('blade-probe', ['label' => 'blade']);
    $response = $this->get($url);

    expect($url)->toStartWith('http://localhost/raster/blade-probe')
        ->and($response->getContent())->toStartWith('<html><body>')
        ->and(state($response))->label->toBe('blade');
});
