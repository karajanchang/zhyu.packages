<?php
namespace Zhyu\Repositories\Eloquents;

use Zhyu\Repositories\Contracts\CriteriaInterface;
use Zhyu\Repositories\Criterias\Criteria;
use Zhyu\Repositories\Contracts\RepositoryInterface;
use Zhyu\Repositories\Exceptions\RepositoryException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Schema;
/**
 * Class Repository
 * @package Zhyu\Repositories\Eloquents
 */
abstract class Repository implements RepositoryInterface, CriteriaInterface {

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var bool
     */
    protected $skipCriteria = false;

    /**
     * @var array
     */
    protected $select = ['*'];



    /**
     * @param App $app
     * @param Collection $collection
     * @throws \Zhyu\Repositories\Exceptions\RepositoryException
     */
    public function __construct(App $app, Collection $collection) {
        $this->app = $app;
        $this->criteria = $collection;
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function model();

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel() {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->model = $model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        return $this->model->get($columns);
    }

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {

        return $this->model->create($data);
    }

    /**
     * @param $id
     * @param array $data
     * @param string $attribute
     * @return mixed
     */
    public function update($id, array $data, $attribute="id") {

        return $this->model->where($attribute, '=', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {

        return $this->model->destroy($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        return $this->model->find($id, $columns);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * @return $this
     */
    public function resetScope() {
        $this->skipCriteria(false);

        return $this;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true){
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria() {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria) {
        $this->model = $criteria->apply($this->model, $this);
        return $this;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria) {
        $this->criteria->push($criteria);
        return $this;
    }

    /**
     * @return $this
     */
    public function  applyCriteria() {
        if($this->skipCriteria === true)
            return $this;

        foreach($this->getCriteria() as $criteria) {
            if($criteria instanceof Criteria) {
                $this->model = $criteria->apply($this->model, $this);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSelect($withParse = false): array
    {
        if($withParse===true){
            return array_map(function($var){
                $exps = explode('.', $var);
                if(count($exps)==2) {
                    return $exps[1];
                }
                return $var;
            }, $this->select);
        }
        if($this->select==['*']) {
            $this->select = Schema::getColumnListing($this->model->getTable());
        }
        return $this->select;
    }

    /**
     * @param array $select
     */
    public function setSelect(array $select): void
    {
        $this->select = $select;
    }


    public function applySelect(array $columns){
        if($columns===['*'] && isset($this->select) && count($this->select)){
            $columns = array_map(function($var){
                $exps = explode('.', $var);
                if(count($exps)==2) {
                    return $var . ' as ' . $exps[1];
                }
                return $var;
            }, $this->select);
        }
        $this->model->select($columns);

        return $columns;
    }

    public function select($columns = ['*']){
        $this->select($columns);
    }

    public function __call($name, $arguments)
    {
        $this->applyCriteria();
        $res = call_user_func_array([$this->model, $name], $arguments);
        return $res;
    }

}

