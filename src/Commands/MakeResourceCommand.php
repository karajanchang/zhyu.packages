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
use Illuminate\Support\Facades\Schema;
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
     * The name of the model.
     *
     * @var string
     */
    private $modelName = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:res {name} {--route=} {--m=}';

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

        /*
        $this->call('make:rcollection',[
            'name' => $name.'Collection'
        ]);

        */
        $this->modelName = ucwords($this->option('m'));

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

        $loop = $this->getReplaceLoop();
        $stub = str_replace('DummyLoop,', $loop, $stub);

        return str_replace('DummyRoute', $this->routeName, $stub);
    }

    /**
     * get Replace loop from database columns.
     *
     * @param  string  $stub
     * @return string
     */
    protected function getReplaceLoop(){
        if(strlen($this->modelName)==0){

            return '';
        }

        $tmp = '\App\\'.$this->modelName;
        $model = app()->make($tmp);
        $columns = Schema::getColumnListing($model->getTable());

        $str = '';
        if(is_array($columns)){
            $stub = "\t\t\t".'\'{DummyColumn}\' => $this->{DummyColumn},'."\r\n";
            foreach($columns as $column){
                if($column=='id') continue;
                $str.= str_replace('{DummyColumn}', $column, $stub);
            }
        }

        return $str;
    }

}