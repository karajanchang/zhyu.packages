<?php


namespace Zhyu\Commands;

use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;

class MakeServiceCommand extends GeneratorCommand
{
    /**
     * The name of the module.
     *
     * @var string
     */
    private $moduleName = null;

    /**
     * The name of the tag.
     *
     * @var string
     */
    private $tagName = null;

    /**
     * The name of the service.
     *
     * @var string
     */
    private $serviceName = '';


    /**
     * The name of repository
     * @var string
     */
    private $repositoryName = '';

    /**
     * The name of error
     * @var string
     */
    private $errorName = '';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhyu:service {name} {--r=} {--repository} {--e=} {--module=} {--tag=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zhyu:service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class (can with tag)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/service.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Services';

        if(!is_null($this->moduleName)){
            $namespace.='\\'.$this->moduleName;
        }

        if(!is_null($this->tagName)){
            $namespace.='\\'.$this->tagName;
        }

        return $namespace;
    }

    public function handle()
    {
        if(!$this->option('r')){
            throw new InvalidOptionException('Missing required option --r for repository name');
        }
        $this->repositoryName = ucwords($this->option('r'));

        if(!$this->option('e')){
            throw new InvalidOptionException('Missing required option --e for error name');
        }
        $this->errorName = ucwords($this->option('e'));

        $module = (string) $this->option('module');
        if(strlen($module)>0) {
            $this->moduleName = ucwords($module);
        }

        $tag = (string) $this->option('tag');
        if(strlen($tag)>0) {
            $this->tagName = ucwords($tag);
        }

        parent::handle();
    }


    /**
     * Replace the Model name for the given stub.
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

        $stub = str_replace('DummyError', $this->errorName, $stub);
        $stub = str_replace('DummyRepository', $this->repositoryName, $stub);


        return $stub;
    }

}
