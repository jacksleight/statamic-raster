---
title: Security
order: 70
---

# Security

Rendering arbitrary views to images on request is the kind of thing that needs a few guards, and Raster has three.

## View Protection

Only views that implement the `raster` tag can be rasterised in automatic mode, an error will be thrown before execution if they don't. That means adding the addon doesn't expose every view in your site, just the ones you've opted in.

## Content Protection

When an entry is passed to a view in automatic mode it goes through the same checks as a request for the entry's own page. Draft entries return a 404, private entries are hidden, and any [protection](https://statamic.dev/protecting-content) scheme on the entry applies, so a password protected page won't leak through its share image.

## URL Signing

It's also recommended to enable URL signing on production to ensure the options and data in a URL can't be tampered with. You can do this by setting the `RASTER_SIGN_URLS` env var to `true`:

```env
RASTER_SIGN_URLS=true
```

Once enabled the URL tag generates signed URLs, and the automatic mode route returns a 401 for any request without a valid signature. [Live preview](live-preview) requests are allowed through, since the control panel builds those URLs itself. Manual mode routes are yours, so protect those however you would any other route.
