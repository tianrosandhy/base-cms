<?php
namespace Module\Site\Services;

use Module\Main\Http\Repository\CrudRepository;
use Module\Main\Services\BaseInstance;
use Module\Site\Exceptions\SiteException;

class BlankInstance extends BaseInstance
{
	public function __construct(){
		$this->data = null;
	}

	public function all($order_by='id', $order_dir='ASC', $ignore_is_active=false, $is_active_field='is_active'){
		return collect([]);
	}

	public function paginate($per_page=10, $ignore_is_active=false, $is_active_field='is_active'){
		return collect([]);
	}

	public function filter($rule=[]){
		return collect([]);
	}

	public function get($id, $pk='id'){
		return null;
	}

	public function setData($var){
		return $this;
	}

}