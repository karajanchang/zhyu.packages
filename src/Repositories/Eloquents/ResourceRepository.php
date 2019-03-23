<?php

namespace Zhyu\Repositories\Eloquents;

use Zhyu\Repositories\Eloquents\Repository;
use Zhyu\Model\Resource;

class ResourceRepository extends Repository
{
	
	public function model()
	{
		return Resource::class;
	}
	
}