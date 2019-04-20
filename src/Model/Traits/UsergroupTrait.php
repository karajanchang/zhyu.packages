<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-20
 * Time: 18:49
 */

namespace Zhyu\Model\Traits;

use App\User;
use App\Usergroup;


trait UsergroupTrait
{
    public function usergroupPermissions(){

        return $this->hasMany(UsergroupPermission::class);
    }

    public function have_permission($resource, $act){
        $count = $this->usergroupPermissions->where('resource_id', $resource->id)->where('act', $act)->count();
        if($count>0){

            return true;
        }

        return false;
    }

    public function children(){
        return $this->hasMany(Usergroup::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}