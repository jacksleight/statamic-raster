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

The addon's route and URL tag only handle Antlers views, and will throw an error for Blade ones. For Blade views use [Laravel Raster](https://github.com/jacksleight/laravel-raster/tree/main/docs) directly, which comes installed with the addon. Add the `@raster` directive to the view and generate the URL with the `raster()` helper, as described in its docs. The view will be rendered inside its own Blade layout, see [layout setup](https://github.com/jacksleight/laravel-raster/blob/main/docs/installation.md#layout-setup).

Blade views don't get the entry cascade or content protection, so pass anything they need as data. Signing and caching follow this addon's config.
