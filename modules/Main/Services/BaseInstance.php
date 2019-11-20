<?php
namespace Module\Main\Services;

use Module\Main\Http\Repository\CrudRepository;

class BaseInstance
{
	public 
		$request, 
		$model,
		$initial;

	public function __construct($initial=''){
		$this->request = request();
		$this->initial = $initial;
		if($initial){
			$this->setModel($initial);
		}
	}

	public function setModel($initial=''){
		$this->model = app(config('model.'.$initial));
		return $this;
	}

	public function all($order_by='id', $order_dir='ASC', $ignore_is_active=true, $is_active_field='is_active'){
		if($ignore_is_active){
			return $this->model->orderBy($order_by, $order_dir)->get();
		}
		else{
			return $this->model->where($is_active_field, 1)->orderBy($order_by, $order_dir)->get();
		}
	}

	public function paginate($per_page=10){
		return $this->model->paginate($per_page);
	}

	public function filter($rule=[]){
		return (new CrudRepository($this->initial))->filter($filter);
	}

}