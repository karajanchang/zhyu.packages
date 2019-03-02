<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\ServiceProvider;

class ZhyuPackagesServiceProvider extends ServiceProvider
{
    public function register(){

    }

    public function boot(){
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Zhyu\ZhyuPackagesServiceProvider::class,
        ];
    }

}
