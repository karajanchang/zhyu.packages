<?php
namespace Zhyu\Model;
use Illuminate\Database\Eloquent\Model;
class Resource extends Model {
	protected $table = 'resources';
	
	public $timestamps = false;
	
	protected $guarded = ['id'];
	
}