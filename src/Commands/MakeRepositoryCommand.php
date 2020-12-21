<?php


namespace Zhyu\Commands;

use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;

class MakeRepositoryCommand extends GeneratorCommand
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
    protected $signature = 'zhyu:repository {name} {--m=} {--model} {--module=} {--tag=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zhyu:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class (can with tag)';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/repository.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace.'\Repositories';

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
        if(!$this->option('m')){
            throw new InvalidOptionException('Missing required option --m for model name');
        }
        $this->modelName = ucwords($this->option('m'));

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

        $stub = str_replace('DummyMapModel', $this->modelName, $stub);

        if($this->option('model')===true){
            $this->modelName = 'Models\\'.$this->modelName;
        }

        return str_replace('DummyModel', $this->modelName, $stub);
    }

}
