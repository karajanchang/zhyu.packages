<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\ServiceProvider;
use Zhyu\Commands\MakeRepositoryCommand;



class ZhyuServiceProvider extends ServiceProvider
{
    protected $commands = [
        MakeRepositoryCommand::class,
    ];

    public function register(){
        $this->loadFunctions();


        $this->app->bind('Ip', function()
        {
            return app()->make(\Zhyu\Tools\Ip::class);
        });

        $this->app->bind('ZhyuCurl', function($app, array $params)
        {
            return $app->make(\Zhyu\Helpers\ZhyuCurl::class, $params);
        });

        $this->app->bind('ZhyuDate', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuDate::class);
        });


        $this->app->bind('ZhyuTool', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuTool::class);
        });

        $this->app->bind('ZhyuUrl', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuUrl::class);
        });

        $this->registerAliases();
    }

    public function boot(){
        if ($this->isLumen()) {

            require_once 'Lumen.php';
        }

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    protected function loadFunctions(){
        foreach (glob(__DIR__.'/functions/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            ZhyuServiceProvider::class,
        ];
    }

    /**
     * Register aliases.
     *
     * @return null
     */
    protected function registerAliases()
    {
        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();

            $loader->alias('Ip', \Zhyu\Facades\Ip::class);
            $loader->alias('ZhyuDate', \Zhyu\Facades\ZhyuDate::class);
            $loader->alias('ZhyuUrl', \Zhyu\Facades\ZhyuUrl::class);
            $loader->alias('ZhyuCurl', \Zhyu\Facades\ZhyuCurl::class);
        }
    }

    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
