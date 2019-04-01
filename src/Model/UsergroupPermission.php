<?php
namespace Zhyu\Model;

use App\Usergroup;
use Zhyu\Model\Resource;
use Illuminate\Database\Eloquent\Model;

class UsergroupPermission extends Model {
	protected $table = 'usergroup_permissions';
	
	public $timestamps = true;
	
	protected $guarded = ['id'];

	public function __toString()
    {
        return $this->resource->name. $this->act;
    }

    public function usergroup(){
	    return $this->belongsTo(Usergroup::class, 'usergroup_id');
    }

    public function resource(){
	    return $this->belongsTo(Resource::class, 'resource_id');
    }
    
}