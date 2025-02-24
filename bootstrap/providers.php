<?php

declare(strict_types=1);

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Laravel\Sanctum\SanctumServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    RouteServiceProvider::class,
    SanctumServiceProvider::class,
];
