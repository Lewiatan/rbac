<?php namespace Lewiatan\Rbac;

use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider {

	const CONFIG = '/config/rbac.php';

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . self::CONFIG => config_path('rbac.php'),
		]);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__. self::CONFIG, 'rbac'
		);

		$this->app->bindShared('rbac', function() {
			return $this->app->make('Lewiatan\Rbac\Rbac');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('rbac');
	}

}
