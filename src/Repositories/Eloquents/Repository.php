<?php
namespace Zhyu\Repositories\Eloquents;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public function makeModel() : Model{
        $model = $this->app->make($this->model());

        if (!$model instanceof Model)
            throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        $this->model = $model;

        return $model;
    }

    /**
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /*
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        $rows = $this->model->get($columns);
        $this->resetModel();

        return $rows;
    }

    /**
     * @param array $columns
     * @param string $cacheKey
     * @param int | Carbon $seconds
     * @return mixed
     */
    public function allCache(array $columns = ['*'], string $cacheKey, $seconds = 600) {
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
    public function paginate(int $perPage = 15, array $columns = ['*']) {
        //dd($this->getCriteria());
        $this->applyCriteria();
        $columns = $this->applySelect($columns);
        $rows = $this->model->paginate($perPage, $columns);
//        dump($this->model->toSql(), $columns);
        $this->resetModel();
//        dump($rows);

        return $rows;
    }

    /**
     * @param null
     * @return array
     */
    public function columns() : array{

        return Schema::getColumnListing($this->model->getTable());
    }

    /**
     * @param array
     * @return array
     */
    public function filterData(array $data) : array{
        $columns = $this->columns();
        array_walk($data, function($value, $key) use($columns, &$data){
            if(!in_array($key, $columns)) {
                unset($data[$key]);
            }
        });

        return $data;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data){

        return $this->model->create($this->filterData($data));
    }

    /**
     * @param array $data
     * @return integer
     */
    public function insertGetId(array $data) {

        return $this->model->insertGetId($this->filterData($data));
    }


    /**
     * @param int $id
     * @param array $data
     * @param string $attribute
     * @return mixed
     */
    public function update(int $id, array $data, string $attribute="id") {

        return $this->model->where($attribute, '=', $id)->update($this->filterData($data));
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id) {

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
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find(int $id, $columns = ['*']) {
        $this->applyCriteria();
        $columns = $this->applySelect($columns);

        $rows = $this->model->find($id, $columns);
        $this->resetModel();

        return $rows;
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $attribute, $value, array $columns = ['*']) {
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
    public function findWhere(array $where, array $columns = ['*']){
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
     * @param int | Carbon $seconds
     *
     * @return mixed
     */
    public function findWhereCache(array $where, array $columns = ['*'], string $cache_key, $seconds = 600){
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
    public function skipCriteria(bool $status = true){
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
     * @param bool $withParse
     * @return array
     */
    public function getSelect(bool $withParse = false): array
    {
        if($withParse===true){
            $this->select = $this->parseSelect($this->select);
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

    /*
     * @param array $select
     * @return array
     */
    private function parseSelect(array $select) : array{
        $parse_select = [];
        foreach($select as $key => $val){
            if($val!='*') {
                $exps = explode('.', $val);
                $var = $val;
                if (count($exps) == 2) {
                    $var = $exps[1];
                }
                if (is_int($key)) {
                    $parse_select[$val] = $var;
                } else {
                    $parse_select[$key] = $val;
                }
            }
        }

        return $parse_select;
    }

    /**
     * @param array $columns
     * @return array
     */
    public function applySelect(array $columns) : array{
        $this->model->select($columns);

        return $columns;
    }

    /**
     * @param mixed $columns
     * @return $this
     */
    public function select($columns = ['*']){
        $this->model = $this->model->select($columns);

        return $this;
    }

    /**
     * Check if entity has relation
     *
     * @param string $relation
     *
     * @return $this
     */
    public function has(string $relation)
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
     * Add wherein.
     *
     * @param int|string $col
     * @param  array $in_array
     * @return $this
     */
    public function whereIn($col, array $in_array)
    {
        $this->model = $this->model->whereIn($col, $in_array);

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
        try {
            $this->applyCriteria();
            $res = call_user_func_array([$this->model, $name], $arguments);
            $this->resetModel();
        }catch (\Exception $e){
            Log::error(__CLASS__.' exception: ', ['name' => $name, 'arguments' => $arguments]);
        }

        return $res;
    }

}
