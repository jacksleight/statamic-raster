---
title: Caching
order: 60
---

# Caching

Generating an image takes a little while, so once you're happy with the output you'll probably want to cache it. Caching is off by default per view and enabled with the `cache` option, either in the main tag or the URL tag.

[TOC]

---

## Options

* **cache (bool, false)**  
  Enable caching of generated images.
* **cache_id (string, '_')**  
  Cache identifier, see [clearing the cache](#clearing-the-cache) below. This one can't be passed as a URL parameter.

Cached files are keyed on every option, the content ID and the data parameters, so a change to the URL produces a new image. File paths will use this pattern:

```
[cache_path]/[view_name]/[cache_id]/[params_hash].[extension]
```

---

## Storage

By default the cache will be stored locally in `storage/app/raster`. You can store it on any filesystem disk instead by setting the `RASTER_CACHE_DISK` env var, and the directory on that disk with `RASTER_CACHE_PATH`, which defaults to `raster`:

```env
RASTER_CACHE_DISK=s3
RASTER_CACHE_PATH=raster
```

You can globally disable caching by setting the `RASTER_CACHE_ENABLED` env var to `false`, which is useful locally while you're working on a view.

---

## Clearing the Cache

There's no expiry, cached images live until you remove them. The `cache_id` option exists so that you can group the images for an entry together and clear them when it changes:

```antlers
{{ %raster
    width="1000"
    cache="true"
    :cache_id="id"
}}
```

Then use the `Cache` class to forget a view's images by ID, or flush the lot:

```php
use JackSleight\LaravelRaster\Cache;

app(Cache::class)->forget('blog.hero', $entry->id());
app(Cache::class)->forget(['blog.hero', 'blog.card'], $entry->id());
app(Cache::class)->flush();
```

An `EntrySaved` event listener is a good place for the first one.
