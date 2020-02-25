<?php
namespace Module\Api\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Api\Exceptions\ApiException;

class ApiInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('api');
	}

}