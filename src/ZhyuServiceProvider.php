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
		$this->app->bind(ModifyButton::class, function ($app, $data, $route, $route_params, $text) {
			return new SimpleButton($data, $route, $route_params, 'btn btn-info btn-circle btn-sm m-l-5', 'ti-pencil-alt', $text);
		});
    }

    public function boot(){
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'zhyu');
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
