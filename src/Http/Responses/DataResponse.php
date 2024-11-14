<?php

namespace JackSleight\StatamicRaster\Http\Responses;

use Statamic\Http\Responses\DataResponse as StatamicDataResponse;

class DataResponse extends StatamicDataResponse
{
    public function verifyResponse($request): void
    {
        $this->request = $request;

        $this
            ->protect()
            ->handleDraft()
            ->handlePrivateEntries();
    }
}
