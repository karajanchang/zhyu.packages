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

class MakeReportCommand extends GeneratorCommand
{
    /**
     * The name of the Repository.
     *
     * @var string
     */
    private $repositoryName = '';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhyu:report {name} {--r=}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zhyu:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a report class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Report';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/report.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Reports';
    }

    public function handle()
    {
        if(!$this->option('r')){
            throw new InvalidOptionException('Missing required option --r for repository name');
        }
        if(!$this->option('act')){
            throw new InvalidOptionException('Missing required option --act for act name');
        }
        $this->repositoryName = ucwords($this->option('r'));
        //--make model class
        $this->info('Done. modify '.$this->name.' in /app/Reports/.');

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
        $stub = str_replace('DummyRepository', $this->repositoryName, $stub);
        
        return $stub;
    }

}