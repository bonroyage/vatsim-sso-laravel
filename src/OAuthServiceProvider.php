<?php namespace Vatsim\OAuthLaravel;

use Illuminate\Support\ServiceProvider;
use Vatsim\OAuth\SSO;

class OAuthServiceProvider extends ServiceProvider {

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
	public function boot ()
	{
		$app = $this->app;

		// Laravel 4.x compatibility
		if ( version_compare($app::VERSION, '5.0') < 0 )
		{
			$this->package('vatsim/sso-laravel', 'vatsim', __DIR__);
		}
		else
		{
			$this->publishes([
				__DIR__ . '/config.php' => config_path('vatsim-sso.php'),
			], 'config');
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register ()
	{
		$this->app->singleton('vatsimoauth', function ( $app )
		{
			// vatsim::config is Laravel 4.x
			$config = $app['config']['vatsim-sso'] ?: $app['config']['vatsim::config'];

			// Make sure we don't crash when we did not publish the config file
			if ( is_null($config) )
			{
				$config = [];
			}

			return new SSO(
				array_get($config, 'base'), // base
				array_get($config, 'key'), // key
				array_get($config, 'secret'), // secret
				array_get($config, 'method'), // method
				array_get($config, 'cert') // certificate
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides ()
	{
		return [SSO::class];
	}

}
