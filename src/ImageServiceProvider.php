<?php


namespace Kovalovme\Image;


use Illuminate\Support\ServiceProvider;
use Kovalovme\Image\Services\ImageService;
use Kovalovme\Image\Services\ImageStorageService;

class ImageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/image.php' => config_path('image.php')
        ]);

        if (! class_exists('App\\Models\\Image')) {
            $this->publishes([
                __DIR__ . '/../resources/models/image.stub' => app_path('Models/Image.php')
            ]);
        }

        if (! class_exists('CreateImagesTable')) {
            $this->publishes([
                __DIR__ . '/../database/migrations/create_images_table.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_images_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->app->bind('image.model', function($app) {
            return $this->app->make(config('image.model'));
        });

        $this->app->bind('image.storage', function($app) {
            return new ImageStorageService(config('image.storage.disk'), config('image.storage.presets'), config('image.storage.default_preset'));
        });

        $this->app->bind('image', function($app) {
            return new ImageService($this->app->make('image.storage'));
        });
    }
}