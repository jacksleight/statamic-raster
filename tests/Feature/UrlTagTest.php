<?php

use Statamic\View\View;

test('url tag parameters are camel cased', function () {
    expect(trim((new View)->template('url-tag-snake-case')->render()))
        ->toBe('http://localhost/!/raster/probe?transparent=1');
});
