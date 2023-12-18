<?php

namespace Pteranodon\Providers;

use Illuminate\Support\ServiceProvider;
use Pteranodon\Http\ViewComposers\StoreComposer;
use Pteranodon\Http\ViewComposers\SettingComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        $this->app->make('view')->composer('*', SettingComposer::class);
        $this->app->make('view')->composer('*', StoreComposer::class);
    }
}
