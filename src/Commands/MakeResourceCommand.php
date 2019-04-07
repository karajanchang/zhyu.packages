<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-07
 * Time: 10:46
 */
namespace Zhyu\Commands;

use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;

class MakeResourceCommand extends GeneratorCommand
{
    /**
     * The name of the route.
     *
     * @var string
     */
    private $routeName = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:res {name} {--route=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:res';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Resources';
    }

    public function handle()
    {
        if(!$this->option('route')){
            throw new InvalidOptionException('Missing required option --route for route name');
        }
        $this->routeName = ucwords($this->option('route'));

        $name = ucwords($this->argument('name'));

        $this->call('make:rcollection',[
            'name' => $name.'Collection'
        ]);

        parent::handle();
    }


    /**
     * Replace the route name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $m
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument repository name");
        }

        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyRoute', $this->routeName, $stub);
    }


}