<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-04-20
 * Time: 18:53
 */

namespace Zhyu\Model\Traits;

use App\Usergroup;

trait UserTrait
{

    public function usergroup(){
        return $this->belongsTo(Usergroup::class);
    }



    public function userPermissions(){
        return $this->hasMany(UserPermission::class);
    }

    public function permissions(){
        $user_permissions = $this->userPermissions;
        if($user_permissions->count()>0){
            return $user_permissions;
        }
        $usergroup_permissions = $this->usergroup->usergroupPermissions;
        return $usergroup_permissions;
    }

    public function have_permission($resource, $act){
        $count = $this->userPermissions->where('resource_id', $resource->id)->where('act', $act)->count();
        if($count>0){

            return true;
        }

        $count = $this->usergroup->usergroupPermissions->where('resource_id', $resource->id)->where('act', $act)->count();

        if($count>0){

            return true;
        }

        return false;

    }
}