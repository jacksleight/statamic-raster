---
title: Advanced
order: 90
---

# Advanced

[TOC]

---

## Customising Browsershot

If you need to customise the Browsershot instance you can pass a closure to `Raster::browsershot()` in a service provider's `boot()` method:

```php
use JackSleight\StatamicRaster\Raster;

Raster::browsershot(fn ($browsershot) => $browsershot
    ->setOption('args', ['--disable-web-security'])
    ->waitUntilNetworkIdle()
);
```

This is where you'd point Browsershot at a specific Node or Chromium binary, adjust timeouts, or wait for assets to finish loading. See the [Browsershot docs](https://spatie.be/docs/browsershot/v4/introduction) for everything available.

---

## Blade Views

Blade views should work alongside Antlers ones, as the addon registers its handlers on top of Laravel Raster's. Use the `@raster` directive instead of the tag, and the `raster()` helper instead of the URL tag, as described in the [Laravel Raster](https://jacksleight.com/code/laravel-raster) docs.
