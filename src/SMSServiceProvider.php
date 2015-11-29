<?php namespace Csgt\SMS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class SMSServiceProvider extends ServiceProvider {

	protected $defer = false;

	public function boot() {
		$this->mergeConfigFrom(__DIR__ . '/config/csgtsms.php', 'csgtsms');
		AliasLoader::getInstance()->alias('SMS','Csgt\SMS\SMS');

		$this->publishes([
      __DIR__.'/config/csgtsms.php' => config_path('csgtsms.php'),
    ], 'config');
	}

	public function register() {
		$this->app['sms'] = $this->app->share(function($app) {
    	return new SMS;
  	});
	}

	public function provides() {
		return array('sms');
	}
}