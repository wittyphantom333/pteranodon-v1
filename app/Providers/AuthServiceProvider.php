<?php

namespace Pteranodon\Providers;

use Pteranodon\Models\ApiKey;
use Pteranodon\Models\Server;
use Laravel\Sanctum\Sanctum;
use Pteranodon\Policies\ServerPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     */
    protected $policies = [
        Server::class => ServerPolicy::class,
    ];

    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(ApiKey::class);

        $this->registerPolicies();
    }

    public function register()
    {
        Sanctum::ignoreMigrations();
    }
}
