<?php

namespace Zhyu\Repositories\Eloquents;

use App\Usergroup;

class UsergroupRepository extends Repository
{
	
	public function model()
	{
		return Usergroup::class;
	}
	
}