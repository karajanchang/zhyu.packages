<?php

namespace Zhyu\Datatables;

class DatatablesService {
	private $datatables;
	
	public function __construct(DatatablesInterface $datatables) {
		$this->datatables = $datatables;
	}
	
	public function table(){
		return $this->datatables->table();
	}
	public function js($varName='table'){
		return $this->datatables->js($varName);
	}
	public function ajax(){
		return $this->datatables->ajax();
	}
	public function all(){
		return $this->datatables->all();
	}
}