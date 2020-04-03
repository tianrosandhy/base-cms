<?php
namespace Module\Contact\Http\Controllers;

use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Http\Controllers\AdminBaseController;
use Module\Contact\Http\Skeleton\ContactSkeleton;
use Core\Main\Transformer\Exportable;
use Core\Main\Contracts\Crud;
use Core\Main\Http\Traits\BasicCrud;

class ContactController extends AdminBaseController implements Crud
{
	use BasicCrud;
	use Exportable;
	//hint => used as route name, url name, view alias
	public $hint = 'contact';

	public function repo(){
		//repo => model alias used (default : same as hint)
		return $this->hint;
	}

	public function skeleton(){
		return new ContactSkeleton;
	}

	public function afterValidation($mode='create', $instance=null){

	}

	public function afterCrud($instance){

	}

	public function image_field(){
		return ['image'];
	}

}