<?php

use Illuminate\Testing\TestResponse;
use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function state(TestResponse $response): array
{
    preg_match('#<script id="state" type="application/json">(.*?)</script>#s', $response->assertOk()->getContent(), $match);

    return json_decode($match[1], true);
}
