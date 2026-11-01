<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use JackSleight\LaravelRaster\Cache;
use JackSleight\StatamicRaster\Raster;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;

Route::statamic('/', 'index', [
    'title' => 'Statamic Raster Lab',
]);

Route::statamic('faults/unresolvable-content', 'faults/unresolvable-content', [
    'title' => 'Unresolvable content',
]);

Route::statamic('faults/url-tag-cache-id', 'faults/url-tag-cache-id', [
    'title' => 'cache_id on the URL tag',
]);

Route::get('lab/{entry}/card', fn ($entry) => Raster::make('raster.card')
    ->content(Entry::find($entry) ?? abort(404))
    ->width(600)
)->name('lab.card');

Route::get('lab/{entry}/card/preview', fn ($entry) => Raster::make('raster.card')
    ->content(Entry::find($entry) ?? abort(404))
    ->width(600)
    ->preview(true)
)->name('lab.card.preview');

Route::get('lab/{entry}/render', function ($entry) {
    $entry = Entry::find($entry) ?? abort(404);

    $data = Raster::make('raster.card')
        ->content($entry)
        ->width(600)
        ->render();

    Storage::put("renders/{$entry->slug()}.png", $data);

    return response($data)->header('Content-Type', 'image/png');
})->name('lab.render');

Route::get('lab/{entry}/cache/clear', function ($entry) {
    $entry = Entry::find($entry) ?? abort(404);

    app(Cache::class)->forget('raster.cached', $entry->id());

    return redirect($entry->url());
})->name('lab.cache.clear');

Route::get('lab/{entry}/manual-tagged', fn ($entry) => Raster::make('raster.hero')
    ->content(Entry::find($entry) ?? abort(404))
    ->width(1200)
)->name('lab.manual-tagged');

Route::get('lab/terms/{term}/card', fn ($term) => Raster::make('raster.card')
    ->content(Term::find("topics::{$term}") ?? abort(404))
    ->width(600)
)->name('lab.term.card');

Route::get('lab/signing/{state}', function ($state) {
    $state === 'on'
        ? Storage::put('lab/signed', '1')
        : Storage::delete('lab/signed');

    return redirect()->back();
})->whereIn('state', ['on', 'off'])->name('lab.signing');
