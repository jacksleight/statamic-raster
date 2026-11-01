<?php

namespace App\Tags;

use Illuminate\Support\Facades\Storage;
use Statamic\Tags\Tags;

class Lab extends Tags
{
    public function helper(): string
    {
        return (string) raster($this->params->get('view'), $this->params->get('data', []));
    }

    public function signing(): string
    {
        return Storage::exists('lab/signed') ? 'on' : 'off';
    }

    public function source(): string
    {
        return e(file_get_contents(resource_path('views/'.$this->params->get('file'))));
    }
}
