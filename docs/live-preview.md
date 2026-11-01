---
title: Live Preview
order: 80
---

# Live Preview

Rasterised views can be added to Statamic's [live preview](https://statamic.dev/live-preview), so editors can see a share image update as they type. Add an additional preview target to the collection config with the automatic mode URL for the view:

```yaml
preview_targets:
  -
    label: Entry
    url: '{permalink}'
  -
    label: 'Share Image'
    url: '/!/raster/blog.hero'
```

The entry being edited is passed to the view automatically, unsaved changes included. Live preview always uses [preview mode](options#preview-mode), so what you see is the scaled HTML rather than a generated image, which keeps it responsive.

If you need to adjust the preview you can add any of the [options](options) as query parameters:

```yaml
url: '/!/raster/blog.hero?width=600'
```

The view must implement the `raster` tag, as with any automatic mode request.
