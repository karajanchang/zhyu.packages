<?php
namespace Zhyu\Model;

use App\User;
use Zhyu\Model\Resource;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model {
	protected $table = 'user_permissions';
	
	public $timestamps = true;
	
	protected $guarded = ['id'];

	public function __toString()
    {
        return $this->resource->name. $this->act;
    }

    public function user(){
	    return $this->belongsTo(User::class, 'user_id');
    }

    public function resource(){
	    return $this->belongsTo(Resource::class, 'resource_id');
    }
    
}