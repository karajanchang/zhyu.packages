<?php

namespace Zhyu\Repositories\Contracts;

Interface RepositoryInterface{

	public function all(array $columns = ['*']);
 
    public function paginate(int $perPage = 15, array $columns = ['*']);
 
    public function create(array $data);
 
    public function update(int $id, array $data);
 
    public function delete(int $id);
 
    public function find(int $id, $columns = ['*']);
 
    public function findBy(string $field, $value, array $columns = ['*']);
}