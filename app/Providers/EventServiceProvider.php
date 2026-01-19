<?php

namespace App\Providers;

use App\Models\Lei;
use App\Observers\LeiObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Lei::observe(LeiObserver::class);
    }
}
