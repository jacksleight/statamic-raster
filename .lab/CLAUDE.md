# Statamic Raster Lab

A Statamic 6 site that exists only to develop and test `jacksleight/statamic-raster`. The addon and the package it builds on, `jacksleight/laravel-raster`, are both pulled in over composer path repositories (`../statamic-raster` and `../laravel-raster`, constraint `*`), so `vendor/jacksleight/*` are symlinks into the packages' working trees and edits there are live here with no rebuild. The lab always sees whatever branch each package is on, including uncommitted work. Neither package has CP assets, so there is nothing to publish or symlink.

Herd parks `~/Packages`, so the site answers on http://statamic-raster--lab.test/ with no vhost step. The control panel is at `/cp`.

## Build steps the overlay does not carry

`composer.json`, `package.json`, `.env` and their locks are not baked into `.lab/`, so a rebuild has to redo these after copying the overlay over a fresh `statamic new` shell:

- **Composer.** `minimum-stability: dev` and `prefer-stable: true`, then two path repositories. laravel-raster's carries a `versions` override, because the addon requires `^1.0` and laravel-raster has no 1.0 tag yet:

  ```
  herd composer config repositories.0 '{"type":"path","url":"../laravel-raster","options":{"versions":{"jacksleight/laravel-raster":"1.0.0"}}}'
  herd composer config repositories.1 path ../statamic-raster
  herd composer require 'jacksleight/laravel-raster:*' 'jacksleight/statamic-raster:*'
  ```

  Drop the override once laravel-raster 1.0 is tagged.
- **npm.** `npm install -D puppeteer @fontsource-variable/fraunces @fontsource-variable/instrument-sans @fontsource-variable/jetbrains-mono`, then `npx puppeteer browsers install chrome-headless-shell`, then `npm run build`. Puppeteer is lab-local on purpose: the global one is too old, and its Chromium renders every `oklch()` colour (all of Tailwind 4's palette) as transparent. `AppServiceProvider` points Browsershot at `base_path('node_modules')` for that reason. If a render reports a missing `chrome-headless-shell` version, rerun the browsers install from here.
- **`.env`.** `statamic new` writes `APP_URL` with `:8000`, which bakes the wrong port into every URL the raster tag generates. Set `APP_URL=http://statamic-raster--lab.test`.

## The raster layout and views

`resources/views/raster.antlers.html` is the layout the addon renders into (its default `layout` config). It loads `site.css` through Vite and sets no background, so a view paints its own and the transparent variants come out with alpha. Fonts are bundled locally through `@fontsource-variable`, so a render never waits on a font host.

| View | Mode | What it exercises |
| ---- | ---- | ----------------- |
| `raster/hero` | Automatic, `basis="700" width="1200"` | The cascade (`title`, `date`, `topics`) and branching on `raster`. `tests/show` also includes it inline as a partial, which proves the tag does nothing outside a raster request |
| `raster/card` | Manual, no tag | The manual routes in `routes/web.php`, for an entry and for a term |
| `raster/cached` | Automatic, `cache="true" :cache_id="id"` | Prints its render time, so a cache hit is visible. `/lab/{id}/cache/clear` forgets it through the `Cache` class |
| `raster/badge` | Automatic, data only | No entry, `label` and `color` from `data`. The colour goes in an inline style because a Tailwind class built from data never compiles |
| `raster/blade-card.blade.php` | Automatic, `@raster` | A Blade view through the addon's route and through laravel-raster's `raster()` helper |
| `raster/untagged` | None | Has no tag, so the automatic route must throw |
| `places/share` | Automatic, `width="1200" height="630" basis="600"` | The demo share card, see Demos |

## Tests

One collection, `tests`, routed at `/tests/{slug}` with template `tests/show` and blueprint `test` (title, summary, date, topics). The entries are the same shape and differ only in publishing state, so they share one blueprint and template rather than one each. The collection is dated with future dates private, and carries a `Share image` preview target pointing at `/!/raster/raster.hero`.

| Entry | State | What it checks |
| ----- | ----- | -------------- |
| `/tests/published` | Published, topic `seafront` | Every automatic mode URL variant hangs off this page: PNG, `src`, preview, JPEG, width, scale, height, PDF, `file`, an unknown `?foo=1`, `?transparent=false`, `?cache=false`, a term as `content`, the signing toggle, caching, the manual routes and both Blade routes |
| `/tests/draft` | `published: false` | Its raster URL 404s like its page |
| `/tests/future` | Dated 2027, future private | Its raster URL 404s like its page |
| `/tests/protected` | `protect: logged_in` | Logged out, its raster URL redirects to the scheme's login URL like its page |

The `topics` taxonomy holds one term, `seafront`, with a `summary` field on its blueprint, for the term cases.

The home page at `/` (template `index`, mounted with `Route::statamic`) lists the tests, then sections for data-only rendering, protection, errors, demos and the CP.

## Routes

`routes/web.php` mounts the index, the two fault pages (below), and the manual mode routes:

- `/lab/{entry}/card`, `/lab/{entry}/card/preview` and `/lab/{entry}/render` (the last calls `render()` and writes `storage/app/private/renders/{slug}.png`)
- `/lab/{entry}/cache/clear`
- `/lab/{entry}/manual-tagged`, which hands the tagged hero to manual mode and must throw
- `/lab/terms/{term}/card`, manual mode with a term from `topics`
- `/lab/signing/on` and `/lab/signing/off`, which create or delete `storage/app/private/lab/signed`. `AppServiceProvider` turns on `raster.sign_urls` and `statamic.raster.sign_urls` while that file exists, so signing can be flipped without editing `.env`. The index and the tests page show the current state

`/faults/url-tag-cache-id` and `/faults/unresolvable-content` are pages whose only job is to call the URL tag in a way that throws, so the throw takes down a page of its own rather than the tests page. Their templates are in `resources/views/faults/`.

`app/Tags/Lab.php` is the lab's own tag: `{{ lab:helper view="…" }}` returns laravel-raster's `raster()` helper as a string, and `{{ lab:signing }}` prints `on` or `off`.

## Demos

A second collection, `demos` (titled Places), routed at `/demos/{slug}` with layout `demo` and template `demos/show`, blueprint `place`: title, kicker (Seafront, Shopping, Landmark), a one-sentence summary and a theme (Sea, Shingle, Sunset). `demos/show` pushes the share card as `og:image` and shows it at full width. A `Share card` preview target puts it in Live Preview at `/!/raster/places.share?width=600`; the width override keeps the 600px preview inside the Live Preview pane, where the full 1200 would overflow it.

| Entry | Theme | Motif |
| ----- | ----- | ----- |
| `/demos/brighton-palace-pier` | Sunset | The pier silhouette against a setting sun |
| `/demos/the-lanes` | Shingle | A cairn of pebbles on a shingle bank |
| `/demos/royal-pavilion` | Sea | Waves under a low sun |

`places/share` is kept to a few clean lines because it is photographed: the marketplace slides show it in a code panel. The theme drives the palette through `data-theme` and custom properties in `site.css`, and the motif comes from `places/_motif`, which switches on the theme and holds the inline SVGs. Demo copy is short, light and written from nobody's point of view.

**The marketplace slides are built from this collection.** The rendered cards and the Live Preview screenshots are captured by the site's `code-capture` manifest (`~/Projects/jacksleight/.claude/skills/code-capture/shots/statamic-raster.json`), and the slides' code panels are `places/share` and the `og:image` block of `demos/show` verbatim. Change either and the matching slide has to change with it.

## Findings

Confirmed in this lab on statamic/cms 6.39 and spatie/browsershot 5.4 against the packages' `feature/docs` working trees, and recorded, not fixed (the fixes are a separate pass):

- **Fixed: every request input was called as a method on the Raster object** (laravel-raster `3e8ba79`, statamic-raster "Only accept option parameters from the request"). `AntlersHandler::injectParams` merged all of `$request->all()` and excluded only `content`, `token` and `live-preview`, so any other query parameter was called as a method: `?foo=1` 500'd with "Call to undefined method Raster::foo()", and with signing on the signed URL's own `signature` did the same, so **every signed URL 500'd**. Both handlers now read `Raster::requestParams()`, which returns only the options in `Raster::$params`. statamic-raster's `Raster` keeps `content` out of `$params`, because `handleRequest()` owns it. `/!/raster/raster.hero?foo=1` is a 200 PNG, a signed URL renders with signing on, and an unsigned one 401s.
- **Fixed: `file` was dropped from generated URLs** (laravel-raster `a64290e`). `gatherParams()` had no `file`, so `{{ raster:raster/hero file="share" }}` produced the same URL as without it. The tests page's URL now carries `&file=share`.
- **The URL tag does not camel case its parameters**, while the index tag does. `{{ raster:raster/cached cache_id="lab" }}` calls `cache_id()`, which does not exist, and takes the page down (`/faults/url-tag-cache-id`). `:cache_id` on the main tag works.
- **Fixed: `?transparent=false` and `?cache=false` coerced to true** (laravel-raster `72f0154`). `Raster::requestParams()` now reads the boolean options with `$request->boolean()`. Before the fix, the string `"false"` was truthy for the `?bool` setters. `/!/raster/raster.badge?transparent=false` comes back with an alpha channel and transparent corners where the bare URL has none (`?transparent=0` works), and `?cache=false` on the uncached hero wrote `storage/app/raster/raster.hero/_/….png`.
- **The Blade claims in `docs/advanced.md` do not hold.** A Blade view through `/!/raster` renders, with the cascade, but with no layout at all: the response is the bare view markup, so the layout's stylesheet never loads. laravel-raster's `raster()` helper returns its own `Raster`, pointing at laravel-raster's route (`/raster/raster.blade-card`), which then 500s here looking for laravel-raster's default Blade layout, `components.layouts.raster`. `BladeHandler::injectParams` excluded nothing, so `?live-preview=1` on a Blade view through the addon's route 500'd and `?token=abc` on laravel-raster's route 500'd on `token()`; fixed in laravel-raster `3e8ba79`, and `/!/raster/raster.blade-card?live-preview=1` is now a 200 PNG. `route:list` shows both `!/raster/{name}` and `raster/{name}` registered.
- **Fixed: `renderPreview` posted `raster.loaded` to the parent and nothing listened** (laravel-raster `ffce2d9`). The script is removed; the only listener ever written was a fieldtype in a statamic-raster WIP commit (`e78c537`) that never shipped.
- **An unresolvable `content` ID 404s the whole page.** `Raster::content()` throws `NotFoundHttpException` when `Data::find()` misses, and the URL tag runs it during the page render, so `/faults/unresolvable-content` is a 404 rather than a page with a broken image. On the automatic route itself (`?content=does-not-exist`) a 404 is right.
- **Fixed: URL `data` never reached an Antlers view in automatic mode** (statamic-raster "Pass URL data to Antlers views"). `injectParams` set the data from inside the view's own `{{ %raster }}` tag, after `renderHtml()` had handed the still empty data to the view, and Antlers does not merge a single tag's array return into the context; it logged "Cannot render an array variable as a string: {{ raster }}" instead. `handleRequest()` now sets the data from `requestParams()` before rendering, and the main tag returns nothing. `/!/raster/raster.badge?data[label]=Hand+written&data[color]=%234338ca` renders "Hand written" in indigo, the index page's URL tag badges show their own labels, and the log line is gone. Data and content set on the main tag itself still cannot reach the view, so the docs list them as URL tag and PHP options.

Found in the website's pre-launch QA on 2026-10-30, in laravel-raster, and also not fixed:

- **Fixed: `flush()` threw with the default cache config** (laravel-raster `ffbe31f`). `ServiceProvider` built the `Cache` with a null directory and `flush()` passed it straight to `exists()`. A null directory now means the disk root, and `cache.path` defaults to `raster`, so a shared disk with no `RASTER_CACHE_PATH` is not wiped by `flush()`.
- **Fixed: `flush()` wiped a shared cache disk** (statamic-raster "Default the cache path to raster"). Found while fixing the rest. The addon's config set `cache.path` to `env('RASTER_CACHE_PATH')` with no default and its `ServiceProvider` copies `statamic.raster.cache` over `raster.cache`, so laravel-raster's `raster` default never applied: with `RASTER_CACHE_DISK` set and no path, images were cached at the disk root and `flush()` deleted every directory on the disk. Proved in tinker against a scratch disk holding `uploads/photo.jpg`. The addon's config now defaults the path to `raster` too. With no disk set the cache has its own `storage/app/raster` disk and was never at risk.
- **Unsigned, the query string set any option.** The same `injectParams` merge that broke signing accepted `?width=`, `?cache=` and `?cacheId=`. Fixed in both handlers (laravel-raster `3e8ba79` and statamic-raster): only the options in `Raster::$params` are read from the URL, so `cacheId` and anything else is ignored. `width`, `scale` and `cache` remain URL options by design, since the helper's chained options travel in the URL, so signing is still the guard against oversized renders.
- **Fixed: a closure option beat the URL value** (laravel-raster `a5cbe8a`). Decided: the URL wins, as `docs/automatic-mode.md` says, and a closure is not called when the URL sets its option. `data` is the exception, since its closure takes the URL data as input.

**Things that work as documented:** PNG, JPEG and PDF output, `width`, `height`, `basis`, `scale`, preview mode, `transparent="true"`, caching with `:cache_id` on the main tag and forgetting through the `Cache` class, the entry and term cascade, draft, future-private and protected entries (404, 404, redirect), the untagged and manual-tagged errors, manual mode for entries and terms, `render()`, and Live Preview, which renders the edited entry's unsaved values into the share card.

## Things to know

- **Statamic 6 preview targets need a `url:` key.** `format:` (the old key) makes every page 500 with "Undefined array key url".
- **Antlers cannot call `raster:transparent`** or any other Raster method from the layout; it reads methods as empty. The layout therefore cannot branch on transparency the way laravel-raster's Blade layout can.
- **Collection dating is `date: true` with `date_behavior`**, not `dated:`. Statamic ignores the wrong key silently and treats every entry as undated, which made the future entry render.
- **A changed raster view needs `npm run build`** if it adds Tailwind classes; the layout loads the built CSS, not a dev server.

## Rebuilding

This lab is disposable, and everything in it worth keeping is baked into `.lab/` in the statamic-raster repo, a flat mirror of these paths copied over a fresh `statamic new` shell (strip the shell's `pages` collection, `content/trees`, `home`, `default` and the default blueprint first), followed by the build steps above. To rebuild, run the `setup-lab` skill. To add something that should survive a rebuild, re-run `setup-lab` with that instruction; it makes the change here, verifies it, and bakes the changed files into `.lab/`. Do not edit `.lab/` by hand, and never sync the whole lab into it.

Entry files are baked verbatim, UUIDs included. There is no seed script.

## Commands

- `npm run build` after changing views or CSS
- `herd php please stache:clear` after editing content YAML by hand
- Always `herd php` and `herd composer`, never bare `php`

## Skip (local login)

Local-only login bypass from the machine's skip package (`~/Packages/skip`), configured in `.claude/skip.json`: `GET /!/skip/{handle}` logs in as `{handle}@example.test` and lands on `?redirect=` (a path on this site) or the handle's own `redirect`. Example: `/!/skip/admin`. A listed handle with no user yet is created on its first login, with the password `password`.
