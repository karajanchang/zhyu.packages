<?php

namespace Zhyu\Repositories\Contracts;

Interface RepositoryInterface{

	public function all($columns = array('*'));
 
    public function paginate($perPage = 15, $columns = array('*'));
 
    public function create(array $data);
 
    public function update($id, array $data);
 
    public function delete($id);
 
    public function find($id, $columns = array('*'));
 
    public function findBy($field, $value, $columns = array('*'));
}