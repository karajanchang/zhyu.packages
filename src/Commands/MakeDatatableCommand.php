<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-07
 * Time: 10:46
 */
namespace Zhyu\Commands;

use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;

class MakeDatatableCommand extends GeneratorCommand
{
    /**
     * The name of the model act.
     *
     * @var string
     */
    private $actName = '';

    /**
     * The name of the model name.
     *
     * @var string
     */
    private $modelName = '';

    /**
     * The name of the resource.
     *
     * @var string
     */
    private $resourceName = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:datatable {name} {--m=} {--act=} {--resource=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:datatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new datatable config';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Datatable';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/datatable.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Datatables';
    }

    public function handle()
    {
        if(!$this->option('m')){
            throw new InvalidOptionException('Missing required option --m for model name');
        }
        $this->modelName = ucwords($this->option('m'));

        if(!$this->option('act')){
            throw new InvalidOptionException('Missing required option --act for act name');
        }
        $this->actName = ucwords($this->option('act'));

        if(!$this->option('resource')){
            throw new InvalidOptionException('Missing required option --resource for resource name');
        }
        $this->resourceName = ucwords($this->option('resource'));

        $name = ucwords($this->argument('name'));


        parent::handle();
    }


    /**
     * Replace the route name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        if(!$this->argument('name')){
            throw new InvalidArgumentException("Missing required argument repository name");
        }

        $stub = parent::replaceClass($stub, $name);

        $stub = str_replace('DummyModel', $this->modelName, $stub);
        $stub = str_replace('DummyAct', $this->actName, $stub);
        $stub = str_replace('DummyResource', $this->resourceName, $stub);

        $loop = $this->getReplaceLoop($stub);
        $stub = str_replace('DummyLoop,', $loop, $stub);


        return $stub;
    }

    /**
     * get Replace loop from database columns.
     *
     * @param  string  $stub
     * @return string
     */
    protected function getReplaceLoop($stub){
        $tmp = '\App\\'.$this->modelName;
        $model = app()->make($tmp);
        $columns = Schema::getColumnListing($model->getTable());

        $str = '';
        if(is_array($columns)){
            $stub =  file_get_contents(__DIR__.'/stubs/datatableLoop.stub');
            foreach($columns as $column){
                if($column=='id') continue;

                $str.= str_replace('{DummyColumn}', $column, $stub);
            }
        }

        return $str;
    }


}