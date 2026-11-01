---
title: Manual Mode
order: 40
---

# Manual Mode

If you would like more control over the routing and how the requests are handled you can define your own routes that return raster responses, and then generate a URL to your image using the usual `route` tag.

The view itself is just a view, with no tag:

```antlers
{{# resources/views/blog/hero.antlers.html #}}
<div>
    <svg>...</svg>
    <h1>{{ title }}</h1>
    <p>{{ date }}</p>
</div>
```

The route builds a `Raster` object with `Raster::make()`, passes the entry with `content()`, sets the [options](options) by chaining methods on to it, and returns it. It's a responsable object, so Laravel takes care of the rest:

```php
/* routes/web.php */
use JackSleight\StatamicRaster\Raster;
use Statamic\Facades\Entry;

Route::get('/blog/{entry}/hero', function ($entry) {
    return Raster::make('blog.hero')
        ->content(Entry::find($entry))
        ->width(1000);
})->name('blog.hero');
```

```antlers
{{# resources/views/layout.antlers.html #}}
<meta property="og:image" content="{{ route:blog.hero :entry="id" }}">
```

Because you own the route you can do whatever you like in it, apply your own middleware, look up the data however suits, or decide the options based on the request. The `content()` method takes the entry itself or its ID, and accepts anything Statamic can put in the cascade, so terms work too.

:::warning
Views rasterised using manual mode must not implement the raster tag, an error will be thrown if they do.
:::

## Rendering Directly

If you want the image data rather than a response, call `render()` instead of returning the object. It returns the raw PNG, JPEG or PDF data, which you could then save to an asset container or attach to an email:

```php
$data = Raster::make('blog.hero')
    ->content($entry)
    ->width(1000)
    ->render();
```
