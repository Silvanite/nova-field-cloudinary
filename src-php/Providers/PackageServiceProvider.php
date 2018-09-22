<?php

namespace Silvanite\NovaFieldCloudinary\Providers;

use Illuminate\Routing\Router;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Silvanite\NovaFieldCloudinary\Adapters\CloudinaryAdapter;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        Storage::extend('cloudinary', function ($app, $config) {
            return new Filesystem(new CloudinaryAdapter($config));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
