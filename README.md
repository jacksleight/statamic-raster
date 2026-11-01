<!-- statamic:hide -->

# Raster

<!-- /statamic:hide -->

Rasterise views and partials to images by simply adding a tag and fetching the URL. Automatic routing, scaling, caching, protection and preview mode. Zero configuration (unless you need it).

## Example

Add the `raster` tag to a view, the current entry's data is passed to it automatically:

```antlers
{{# resources/views/blog/hero.antlers.html #}}
{{ %raster
    width="1000"
}}
<div>
    <svg>...</svg>
    <h1>{{ title }}</h1>
    <p>{{ date }}</p>
</div>
```

Then fetch the URL with the `raster:url` tag:

```antlers
{{# resources/views/blog/show.antlers.html #}}
{{ push:head }}
    <meta property="og:image" content="{{ raster:blog/hero }}">
{{ /push:head }}
```

## Documentation

[Statamic Raster Documentation](https://jacksleight.com/code/statamic-raster)

<!-- statamic:hide -->

## Sponsoring 

This addon is completely free to use. However fixing bugs, adding features and helping users takes time and effort. If you find this useful and would like to support its development any [contribution](https://github.com/sponsors/jacksleight) would be greatly appreciated. Thanks! 🙂

<!-- /statamic:hide -->
