<?php

namespace Zhyu\Repositories\Eloquents;

use App\User;

class UserRepository extends Repository
{
	
	public function model()
	{
		return User::class;
	}
	
}