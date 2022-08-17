<?php

namespace Silvanite\NovaFieldCloudinary\Providers;

use Illuminate\Routing\Router;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\FilesystemAdapter;
use CarlosOCarvalho\Flysystem\Cloudinary\CloudinaryAdapter;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Storage::extend('cloudinary', function ($app, $config) {
            if (!empty(env('CLOUDINARY_URL'))){
                $adapter = new CloudinaryAdapter();
            } else {
                $adapter = new CloudinaryAdapter($config);
            }

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
