---
title: Automatic Mode
order: 30
---

# Automatic Mode

In automatic mode Raster handles the routing for you. You add the `raster` tag to a view to make it rasterisable, then generate a URL to the image with the `raster:url` tag.

[TOC]

---

## The Tag

Add the `raster` tag to the top of the view, and set any [options](options) you need as parameters. The `%` prefix tells Antlers this is the tag rather than the `raster` variable, which is also present in the view.

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

When the view is rendered during normal non-raster requests the tag does nothing, so the same view can be used both ways.

:::warning
Views rasterised using automatic mode must implement the raster tag, an error will be thrown if they don't. Views used in [manual mode](manual-mode) must not.
:::

---

## The URL Tag

Generate a URL to the image with the `raster:url` tag. The view name can be passed as the second part of the tag name, with slashes rather than dots, or through the `src` parameter. These two are equivalent:

```antlers
{{# resources/views/blog/show.antlers.html #}}
{{ push:head }}
    <meta property="og:image" content="{{ raster:blog/hero }}">
{{ /push:head }}
```
```antlers
{{ raster:url src="blog/hero" }}
```

You can override [options](options) set in the main tag by passing them to the URL tag. Options passed in the URL take priority:

```antlers
{{ raster:blog/hero width="1200" type="jpeg" }}
```

---

## Content

The current entry is detected automatically and passed to the view, so the entry's fields are available in the cascade just like they would be in its own template. That's why the view above can use `{{ title }}` and `{{ date }}` directly.

To rasterise a different entry pass it, or its ID, with the `content` parameter:

```antlers
{{ raster:blog/hero :content="related_post" }}
{{ raster:blog/hero :content="related_post:id" }}
```

The entry is loaded through Statamic's own data response, so the same [protection](security) rules as its page apply.

---

## Data

To pass anything else to the view use the `data` parameter with an array. The values end up in the URL, so keep them to simple scalars:

```antlers
{{ raster:blog/hero :data="['theme' => 'dark']" }}
```

They're then available in the view by name, `{{ theme }}` in this case.
