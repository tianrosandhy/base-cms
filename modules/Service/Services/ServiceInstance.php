<?php
namespace Module\Service\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Service\Exceptions\ServiceException;

class ServiceInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('service');
	}

	public function categories(){
		return app(config('model.service_category'))->where('is_active', 1)->get();
	}

}