<?php


namespace Image\Providers;


use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/config/image.php' => config_path('image.php'),
        ]);

        if (! class_exists('CreateImagesTable')) {
            $this->publishes([
                __DIR__.'/../resources/migrations/create_images_table.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_images_table.php'),
            ], 'migrations');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../resources/config/image.php', 'image'
        );
    }

}