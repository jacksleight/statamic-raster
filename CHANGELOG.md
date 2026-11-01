# Changelog

## 1.0.0 (2026-11-02)
- [break] Only option parameters are read from the URL, anything else is ignored and `cache_id` can no longer be set there
- [break] Cache path defaults to `raster` when a cache disk is set, published configs need the same default
- [break] Blade views are no longer rendered by the addon's route, tags or Raster class, use Laravel Raster's
- [new] Transparent option
- [new] Entry IDs can be passed as the content option
- [fix] Error when rendering a view without content
- [fix] Signed URLs and unknown query parameters causing errors
- [fix] Boolean URL options treating `false` as true
- [fix] URL data not reaching Antlers views
- [fix] Unresolvable content IDs breaking the page in the URL tag
- [fix] Snake case parameters causing errors in the URL tag
- Dropped support for Statamic 5
- Dropped support for PHP 8.1
- Laravel Raster 1.0 is now required

## 0.5.0 (2025-10-03)

- [new] Live preview support

## 0.4.1 (2025-09-16)

- Statamic 6 support

## 0.4.0 (2024-12-06)

- [new] File name option

## 0.3.1 (2024-11-25)

- [fix] Disk based caching

## 0.3.0 (2024-11-25)

- [new] Disk based caching

## 0.2.1 (2024-11-23)

- [fix] Composer dependency

## 0.2.0 (2024-11-22)

- [new] Global cache toggle
- [new] Cache key option

## 0.1.2 (2024-11-15)

- [fix] URL param overrides

## 0.1.1 (2024-11-15)

- [fix] Loading helpers file

## 0.1.0 (2024-11-15)

- 🚀 Initial release
