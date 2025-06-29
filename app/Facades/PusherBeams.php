<?php

namespace App\Facades;

use App\Service\PusherBeamService;
use Illuminate\Support\Facades\Facade;

class PusherBeams extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PusherBeamService::class;
    }
}
