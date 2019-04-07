<?php
namespace Zhyu\Repositories\Eloquents;

use Illuminate\Support\Facades\Cache;
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
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        $rows = $this->model->get($columns);
        $this->resetModel();

        return $rows;
    }
	
	/**
	 * @param array $columns
	 * @param string $cacheKey
	 * @param int $seconds
	 * @return mixed
	 */
	public function allCache($columns = array('*'), $cacheKey, $seconds = 600) {
		$rows = Cache::remember($cacheKey, $seconds, function() use($columns){
			return $this->all($columns);
		});
		return $rows;
	}

    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);


        $rows = $this->model->paginate($perPage, $columns);
        $this->resetModel();

        return $rows;
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
     * Delete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     */
    public function deleteWhere(array $where){
        $this->applyConditions($where);
        $deleted = $this->model->delete();
        $this->resetModel();

        return $deleted;
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        $rows = $this->model->find($id, $columns);
        $this->resetModel();

        return $rows;
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

        $row = $this->model->where($attribute, '=', $value)->first($columns);
        $this->resetModel();

        return $row;
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*']){
        $this->applyCriteria();
        $this->applyConditions($where);
        $model = $this->model->get($columns);
        $this->resetModel();

        return $model;
    }
	
	/**
	 * Find data by multiple fields
	 *
	 * @param array $where
	 * @param array $columns
	 * @param string $cache_key
	 * @param int $seconds
	 *
	 * @return mixed
	 */
	public function findWhereCache(array $where, $columns = ['*'], $cache_key, $seconds = 600){
		$model = Cache::remember($cache_key, $seconds, function() use($where, $columns){
			
			return $this->findWhere($where, $columns);
		});
		
		return $model;
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

    /**
     * Check if entity has relation
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has($relation)
    {
        $this->model = $this->model->has($relation);
        return $this;
    }
    /**
     * Load relations
     *
     * @param array|string $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        $this->model = $this->model->with($relations);
        return $this;
    }
    /**
     * Add subselect queries to count the relations.
     *
     * @param  mixed $relations
     * @return $this
     */
    public function withCount($relations)
    {
        $this->model = $this->model->withCount($relations);
        return $this;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->model = $this->model->where($field, $condition, $val);
            } else {
                $this->model = $this->model->where($field, '=', $value);
            }
        }
    }

    public function __call($name, $arguments)
    {
        $this->applyCriteria();
        $res = call_user_func_array([$this->model, $name], $arguments);
        $this->resetModel();

        return $res;
    }

}

