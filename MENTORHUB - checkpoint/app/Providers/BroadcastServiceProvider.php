<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register broadcasting routes for all guards
        Broadcast::routes(['middleware' => ['web', 'auth:student']]);
        Broadcast::routes(['middleware' => ['web', 'auth:tutor']]);
        Broadcast::routes(['middleware' => ['web', 'auth:web']]);

        // Move channel definitions to routes/channels.php
        require base_path('routes/channels.php');
    }
} 