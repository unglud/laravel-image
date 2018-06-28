<?php
declare(strict_types=1);

namespace Unglued\LavaImage;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

/**
 * Class LavaImageServiceProvider
 * @package Unglued\LavaImage
 */
class LavaImageServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
    public function boot(): void
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
    public function register(): void
    {
        $this->app->bind('lavaimage', function(){
            return new LavaImage(new ImageManager);
        });
	}

}
