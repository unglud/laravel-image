<?php namespace Unglued\LavaImage;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class LavaImageServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->publishes([
            __DIR__.'/config/lavaimage.php' => config_path('lavaimage.php')
        ]);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('lavaimage', function(){
            return new LavaImage(new ImageManager);
        });
	}

}
