<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-03-26
 * Time: 13:26
 */

namespace Zhyu\Helpers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Zhyu\Repositories\Eloquents\ResourceRepository;
use Zhyu\Repositories\Eloquents\UsergroupPermissionRepository;
use Zhyu\Repositories\Eloquents\UserPermissionRepository;

class ZhyuGate
{
    private $resourceRepository;
    private $userPermission;
    private $usergroupPermission;

    public function __construct(ResourceRepository $resourceRepository, UserPermissionRepository $userPermission, UsergroupPermissionRepository $usergroupPermission)
    {
        $this->resourceRepository = $resourceRepository;
        $this->userPermission = $userPermission;
        $this->usergroupPermission = $usergroupPermission;
    }

    public function init()
    {
        Gate::before(function($user, $ability){
            $user_ids = explode(',', env('ZHYU_ADMIN_USER_IDS'));
            if(in_array($user->id, $user_ids)){
                return true;
            }
        });


        if(Schema::hasTable('resources')) {
            $resources = $this->resourceRepository->findWhere([
                ['parent_id', '>', '0']
            ]);

            $userPermissions = $this->userPermission->all();
            $usergroupPermissions = $this->usergroupPermission->all();

            foreach ($resources as $resource) {
                $parent_route_name = $resource->parent->route;
                $parent_route = strlen($parent_route_name) > 0 ? $parent_route_name . '.' : '';

                $permissions = $userPermissions->where('resource_id', $resource->id);
                if ($permissions->count() > 0) {
                    $permissions->map(function ($permission) use ($resource, $parent_route, $userPermissions, $usergroupPermissions) {
                        $name = $parent_route . $resource->route . '.' . $permission->act;
                        Gate::define($name, function ($user) use ($resource, $permission, $userPermissions, $usergroupPermissions) {
                            $user_pers = $userPermissions->where('user_id', $user->id);
                            if ($user_pers->count() > 0) {
                                return $user_pers->where('resource_id', $resource->id)->where('act', $permission->act)->count() > 0 ? true : false;
                            } else {
                                $usergroup_pers = $usergroupPermissions->where('usergroup_id', $user->usergroup->id);
                                return $usergroup_pers->where('resource_id', $resource->id)->where('act', $permission->act)->count() > 0 ? true : false;
                            }
                        });
                    });
                }

                $permissions = $usergroupPermissions->where('resource_id', $resource->id);
                if ($permissions->count() > 0) {
                    $permissions->map(function ($permission) use ($resource, $parent_route, $usergroupPermissions) {
                        $name = $parent_route . $resource->route . '.' . $permission->act;
                        Gate::define($name, function ($user) use ($resource, $permission, $usergroupPermissions) {
                            $usergroup_pers = $usergroupPermissions->where('usergroup_id', $user->usergroup->id);
                            return $usergroup_pers->where('resource_id', $resource->id)->where('act', $permission->act)->count() > 0 ? true : false;
                        });
                    });
                }
            }
        }
    }
}