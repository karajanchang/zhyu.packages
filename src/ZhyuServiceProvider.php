<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\ServiceProvider;
use Zhyu\Decorates\SimpleButton;

class ZhyuServiceProvider extends ServiceProvider
{
	public function register(){
		$this->app->bind('decorate.ModifyButton', function ($app, $params) {
			return new SimpleButton($params['data'], $params['url'], 'btn btn-info btn-circle btn-sm m-l-5', 'ti-pencil-alt', null, $params['text']);
		});
	}
	
	public function boot(){
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadTranslationsFrom(__DIR__.'/lang', 'zhyu');

		$this->loadViewsFrom(__DIR__.'/blades', 'zhyu');

		$this->publishes([
		    __DIR__.'/blades' => resource_path('views/vendor/zhyu'),
        ]);
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			Zhyu\ZhyuServiceProvider::class,
		];
	}
	
}
