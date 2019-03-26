<?php

namespace Zhyu\Repositories\Eloquents;

use Zhyu\Model\Resource;

class ResourceRepository extends Repository
{
	
	public function model()
	{
		return Resource::class;
	}
	
}