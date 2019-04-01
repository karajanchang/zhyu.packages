<?php

namespace Zhyu\Repositories\Eloquents;

use Zhyu\Model\UserPermission;

class UserPermissionRepository extends Repository
{
	
	public function model()
	{
		return UserPermission::class;
	}
	
}