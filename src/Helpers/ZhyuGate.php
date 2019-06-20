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
use Illuminate\Support\Facades\Log;

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
            if(is_array($user_ids) && in_array($user->id, $user_ids)){

                return true;
            }
        });

        if(Schema::hasTable('resources') && Schema::hasTable('user_permissions') && Schema::hasTable('usergroup_permissions')) {
            $resources = $this->resourceRepository->findWhereCache([
                ['parent_id', '>', '0']
            ], ['*'], env('APP_TYPE') . 'ZhyuParentResources', now()->addMinutes(60));

            $userPermission = $this->userPermission->getModel();
            $usergroupPermission = $this->usergroupPermission->getModel();

            foreach ($resources as $resource) {
                $parentRouteName = $resource->parent->route;
                $parentRoute = strlen($parentRouteName) > 0 ? $parentRouteName . '.' : '';

                $resourceId = $resource->id;
                $condition['resource_id'] = $resourceId;
                $userPermList = $userPermission::where($condition)->get();
                $userGroupPermList = $usergroupPermission::where($condition)->get();

                $actList = [];
                foreach ($userPermList as $list) {
                    if (isset($actList[$list->act])) {
                        continue;
                    }
                    $actList[$list->act] = $list->act;
                }

                foreach ($userGroupPermList as $list) {
                    if (isset($actList[$list->act])) {
                        continue;
                    }
                    $actList[$list->act] = $list->act;
                }

                foreach ($actList as $act) {
                    $gateName = $this->resolveName($parentRoute, $resource->route, $act);

                    Gate::define($gateName, function ($user) use ($resourceId, $act, $userPermission, $usergroupPermission) {

                        $userId = $user->id;
                        $userGroupId = $user->usergroup->id;

                        $condition['act'] = $act;
                        $condition['resource_id'] = $resourceId;

                        $userPermExist = function() use($userPermission, $condition, $userId) { return $userPermission::where($condition)->where('user_id', $userId)->exists(); };
                        $userGroupPermExist = function() use ($usergroupPermission, $condition, $userGroupId) { return $usergroupPermission::where($condition)->where('usergroup_id', $userGroupId)->exists(); };

                        return $userPermExist() || $userGroupPermExist();
                    });
                }
            }
        }
    }

    private function resolveName($parent_route, $resource_route, $act){
        if(strlen($resource_route)>0) {

            return $parent_route.$resource_route.'.'.$act;
        }else{

            return $parent_route.$act;
        }
    }
}