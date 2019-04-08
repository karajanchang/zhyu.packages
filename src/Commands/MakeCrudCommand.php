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

class MakeCrudCommand extends GeneratorCommand
{
    /**
     * The name of the datatable.
     *
     * @var string
     */
    private $datatableName = '';

    /**
     * The name of the model.
     *
     * @var string
     */
    private $modelName = '';

    /**
     * The name of the Repository.
     *
     * @var string
     */
    private $repositoryName = '';

    /**
     * The name of the Route.
     *
     * @var string
     */
    private $routeName = '';

    /**
     * The name of the Resource.
     *
     * @var string
     */
    private $resourceName = '';

    /**
     * The name of the model act.
     *
     * @var string
     */
    private $actName = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name} {--r=} {--m=} {--datatable=} {--route=} {--resource=} {--act=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a crud contorller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Crud';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/CrudController.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    public function handle()
    {
        if(!$this->option('r')){
            throw new InvalidOptionException('Missing required option --r for repository name');
        }
        if(!$this->option('m')){
            throw new InvalidOptionException('Missing required option --m for model name');
        }
        if(!$this->option('datatable')){
            throw new InvalidOptionException('Missing required option --datatable for datatable name');
        }
        if(!$this->option('route')){
            throw new InvalidOptionException('Missing required option --route for route name');
        }
        if(!$this->option('resource')){
            throw new InvalidOptionException('Missing required option --resource for resource name');
        }

        if(!$this->option('act')){
            throw new InvalidOptionException('Missing required option --act for act name');
        }

        $this->repositoryName = ucwords($this->option('r'));
        $this->modelName = ucwords($this->option('m'));
        $this->datatableName = ucwords($this->option('datatable'));
        if(!stristr($this->datatableName, 'datatable')){
            $this->datatableName.='Datatable';
        }
        $this->routeName = $this->option('route');
        $this->resourceName = ucwords($this->option('resource'));
        $this->actName = ucwords($this->option('act'));

        //--make model class
        $this->call('make:model', [
            'name' => $this->modelName,
        ]);

        //---make repository class
        $this->call('make:repository', [
            'name' => $this->repositoryName,
            '--m' => $this->modelName,
        ]);

        //---make resource class
        $this->call('make:res', [
            'name' => $this->resourceName,
            '--m' => $this->modelName,
            '--route' => $this->routeName,
        ]);

        //---make datatable class
        $this->call('make:datatable', [
            'name' => $this->datatableName,
            '--m' => $this->modelName,
            '--act' => $this->actName,
            '--resource' => $this->resourceName,
        ]);

        $this->info('Done. Append '.$this->modelName.'.'.$this->actName.' in /config/criteria.php, if have need.');


        parent::handle();
    }

    /**
     * Replace the Model name for the given stub.
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

        $stub = str_replace('DummyRepository', $this->repositoryName, $stub);
        $stub = str_replace('DummyModel', $this->modelName, $stub);
        $stub = str_replace('DummyRoute', $this->routeName, $stub);

        return $stub;
    }

    /**
     * write to /config/criteria.php
     *
     * @return null
     */
    protected function writeCriteria(){
        /*
         *
        use Larapack\ConfigWriter\Repository;
        $config = new Repository('criteria');
        $vars = $config->get($this->modelName);
        $vars[$this->actName] = [];
        $config->set($this->modelName, $vars);
        $config->save();
        */
    }


}