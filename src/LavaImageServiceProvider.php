<?php namespace Unglued\LavaImage;

use Illuminate\Support\ServiceProvider;

class LavaImageServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
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
        $this->app->bind('lavaimage', function(){
            return new LavaImage();
        });
	}

}
