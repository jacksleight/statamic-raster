<?php

test('a view renders through the route', function () {
    expect(state($this->get('/!/raster/probe')))->width->toBe(100);
});
