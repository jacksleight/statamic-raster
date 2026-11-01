<?php

use Statamic\View\View;

test('an unresolvable content id renders an empty url', function () {
    expect(trim((new View)->template('url-tag-missing-content')->render()))->toBe('');
});

test('an unresolvable content id 404s on the route', function () {
    $this->get('/!/raster/probe?content=does-not-exist')->assertNotFound();
});
