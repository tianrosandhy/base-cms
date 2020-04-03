<?php
namespace Module\Service\Services;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Services\BaseInstance;
use Module\Service\Exceptions\ServiceException;

class ServiceInstance extends BaseInstance
{
	public function __construct(){
		parent::__construct('service');
	}

	public function categories($homepage_only=false){
		$data = model('service_category')->where('is_active', 1);
		if($homepage_only){
			$data = $data->where('show_on_homepage', 1);
		}
		return $data->get();
	}

}