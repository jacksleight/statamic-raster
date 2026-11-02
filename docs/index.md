---
title: Introduction
order: 10
---

# Introduction

Rasterise views and partials to images by simply adding a tag and fetching the URL. Automatic routing, scaling, caching, protection and preview mode. Zero configuration (unless you need it).

It's useful for things like:

* Generating Open Graph and social sharing images for every entry from your own templates
* Rendering charts, badges or cards that need to be embedded as images
* Producing single page PDFs from a view
* Any time you'd rather build an image with HTML and CSS than an image library

## How It Works

Raster renders a view inside a layout, hands the HTML to a headless browser via [Puppeteer](https://pptr.dev/), and returns the screenshot as the response. There are two ways to use it:

* **[Automatic mode](automatic-mode)** is the quick way. You add the `raster` tag to a view, and Raster provides the route, the URL tag and the parameter handling for you. The current entry is passed to the view automatically.
* **[Manual mode](manual-mode)** is for when you'd like to own the routing yourself. Your route builds a `Raster` object and returns it as the response.

Either way the [options](options) are the same, as are [caching](caching), [security](security) and the Browsershot [customisation](advanced) hooks. Rasterised views can also be added to the control panel's [live preview](live-preview).

## Laravel Raster

This addon builds on the [Laravel Raster](https://github.com/jacksleight/laravel-raster/tree/main/docs) package, adding Antlers support, the entry cascade, live preview and Statamic's content protection. Blade views work too, exactly as they do in that package.
