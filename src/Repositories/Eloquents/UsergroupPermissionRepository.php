<?php

namespace Zhyu\Repositories\Eloquents;

use Zhyu\Model\UsergroupPermission;

class UsergroupPermissionRepository extends Repository
{
	
	public function model()
	{
		return UsergroupPermission::class;
	}
	
}