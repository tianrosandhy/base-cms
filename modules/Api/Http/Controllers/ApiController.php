<?php
namespace Module\Api\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Api\Http\Skeleton\ApiSkeleton;
use Core\Main\Transformer\Exportable;

class ApiController extends AdminBaseController
{
	use Exportable;
	//hint => used as route name, url name, view alias
	public $hint = 'api';

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new ApiSkeleton;
	}

	public function afterValidation($mode='create', $instance=null){

	}

	public function afterCrud($instance){
		$public = sha1(uniqid() . $instance->api_name . time());
		if(empty($this->request->public)){
			$instance->public = $public;
		}
		if(empty($this->request->secret)){
			$instance->secret = substr(encrypt($public), 10, 80);
		}
		$instance->save();
	}

	public function image_field(){
		return ['image'];
	}

}