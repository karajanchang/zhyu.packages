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

class MakeRepositoryCommand extends GeneratorCommand
{
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
    protected $signature = 'zhyu:repository {name} {--m=}';

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
    protected $description = 'Create a new repository class';

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
        return $rootNamespace.'\Repositories';
    }

    public function handle()
    {
        if(!$this->option('m')){
            throw new InvalidOptionException('Missing required option --m for model name');
        }
        $this->modelName = ucwords($this->option('m'));

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

        return str_replace('DummyModel', $this->modelName, $stub);
    }


}