<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-02
 * Time: 05:36
 */

namespace Zhyu;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Zhyu\Commands\MakeCrudCommand;
use Zhyu\Commands\MakeDatatableCommand;
use Zhyu\Commands\MakeRepositoryCommand;
use Zhyu\Commands\MakeResourceCollectionCommand;
use Zhyu\Commands\MakeResourceCommand;
use Zhyu\Commands\MakeReportCommand;
use Zhyu\Decorates\Buttons\NormalButton;
use Zhyu\Decorates\Buttons\SimpleButton;

use Zhyu\Report\Media\CsvReport;
use Zhyu\Report\Media\ExcelReport;
use Zhyu\Report\Media\PdfReport;
use Zhyu\Report\ReportFactory;
use Zhyu\Report\ReportService;


class ZhyuServiceProvider extends ServiceProvider
{
    protected $commands = [
        MakeCrudCommand::class,
        MakeRepositoryCommand::class,
        MakeResourceCommand::class,
        MakeResourceCollectionCommand::class,
        MakeDatatableCommand::class,
        MakeReportCommand::class,
    ];

    public function register(){
        if(!$this->isLumen()) {
            $this->app->bind('button.create', function ($app, $params) {
                return new NormalButton(@$params['data'], @$params['url'], 'btn btn-info m-r-15', 'fa fa-plus fa-fw', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.edit', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-info btn-circle btn-sm m-l-5', 'ti-pencil-alt', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.show', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-warning btn-circle btn-sm m-l-5', 'ti-file', null, $params['text'], @$params['title']);
            });
            $this->app->bind('button.destroy', function ($app, $params) {
                return new SimpleButton($params['data'], @$params['url'], 'btn btn-danger btn-circle btn-sm m-l-5', 'ti-trash', null, $params['text'], @$params['title']);
            });
        }

        $this->app->bind('zhyuDate', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuDate::class);
        });

        $this->app->bind('zhyuGate', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuGate::class);
        });

        $this->app->bind('zhyuUrl', function()
        {
            return app()->make(\Zhyu\Helpers\ZhyuUrl::class);
        });

        $this->app->bind('zhyuCurl', function($app, array $params)
        {
            return $app->make(\Zhyu\Helpers\ZhyuCurl::class, $params);
        });

        $this->app->bind('zhyuReport', function($app, array $params)
        {
            if(!isset($params['name']) || strlen($params['name'])==0){
                throw new \Exception('Please provider one class for report to bind');
            }
            ReportFactory::bind($params['name']);
            $service = $app->make(ReportService::class);
            return $service;
        });


        $configPath = __DIR__.'/config/report-generator.php';
        $this->mergeConfigFrom($configPath, 'zhyu');
        $this->app->bind('pdf.report', function ($app) {
            return new PdfReport ($app);
        });
        $this->app->bind('excel.report', function ($app) {
            return new ExcelReport ($app);
        });
        $this->app->bind('csv.report', function ($app) {
            return new CsvReport ($app);
        });
        $this->app->register('Maatwebsite\Excel\ExcelServiceProvider');

        $this->registerAliases();
    }

    public function boot(){
        if ($this->isLumen()) {
            require_once 'Lumen.php';
        }else {

            $must_exists_classes = [
                \App\User::class,
                \App\Usergroup::class,
            ];
            foreach ($must_exists_classes as $class) {
                if (env('ZHYU_RESOURCE_ENABLE', false) === true && !class_exists($class)) {
                    throw new \Exception('this file must exists: ' . $class);
                }
            }

            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
            $this->loadTranslationsFrom(__DIR__ . '/lang', 'zhyu');

            $this->loadViewsFrom(__DIR__ . '/blades', 'zhyu');
            $this->loadMigrationsFrom(__DIR__ . '/databases/migrations');

            $this->publishes([
                __DIR__ . '/blades' => resource_path('views/vendor/zhyu'),
                __DIR__ . '/assets/js' => resource_path('js'),
                __DIR__ . '/lang/en' => resource_path('lang/en'),
                __DIR__ . '/lang/tw' => resource_path('lang/tw'),
                __DIR__ . '/assets/public_js' => public_path('js'),
                __DIR__ . '/config/report-generator.php' => config_path('report-generator.php'),
            ], 'zhyu');

            $this->publishes([
                __DIR__ . '/Http/Resources' => app_path('Http/Resources'),
            ], 'zhyu:view');

            if (env('ZHYU_RESOURCE_ENABLE', false) && Schema::hasTable('resources')) {
                View::composer('vendor.zhyu.blocks.sidemenu', 'Zhyu\Http\View\Composers\Sidemenu');
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
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
            Zhyu\ZhyuServiceProvider::class,
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
            $loader->alias('ZhyuGate', \Zhyu\Facades\ZhyuGate::class);
            $loader->alias('ZhyuUrl', \Zhyu\Facades\ZhyuUrl::class);
            $loader->alias('ZhyuCurl', \Zhyu\Facades\ZhyuCurl::class);
            $loader->alias('PdfReport', \Zhyu\Facades\PdfReport::class);
            $loader->alias('ExcelReport', \Zhyu\Facades\ExcelReport::class);
            $loader->alias('CsvReport', \Zhyu\Facades\CsvReport::class);
        }
    }

    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }
}
