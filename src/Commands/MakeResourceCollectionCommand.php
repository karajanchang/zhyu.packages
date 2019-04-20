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

class MakeResourceCollectionCommand extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zhyu:rcollection {name}';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'zhyu:rcollection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource collection class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ResourceCollection';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/resourceCollection.stub';
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

        parent::handle();
    }

}