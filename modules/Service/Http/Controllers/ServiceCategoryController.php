<?php
namespace Module\Service\Http\Controllers;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Http\Controllers\AdminBaseController;
use Module\Service\Http\Skeleton\ServiceCategorySkeleton;
use Module\Main\Transformer\Exportable;

class ServiceCategoryController extends AdminBaseController
{
	use Exportable;
	//hint => used as route name, url name, view alias
	public $hint = 'service_category';
	public $as_ajax = true;

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new ServiceCategorySkeleton;
	}

	public function afterValidation($mode='create', $instance=null){

	}

	public function afterCrud($instance){

	}

}