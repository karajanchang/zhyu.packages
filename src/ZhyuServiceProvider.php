<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\ServiceProvider;

class ZhyuServiceProvider extends ServiceProvider
{
    public function register(){

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
