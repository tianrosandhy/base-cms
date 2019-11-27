<?php
namespace Module\Main\Services;

use Module\Main\Http\Repository\CrudRepository;
use Illuminate\Database\Eloquent\Model;
use Module\Main\Exceptions\InstanceException;

class BaseInstance
{
	public 
		$request, 
		$model,
		$initial,
		$data;

	protected $message;

	public function __construct($initial=''){
		$this->request = request();
		$this->initial = $initial;
		if($initial){
			$this->setModel($initial);
		}
		$this->data = null;
		$this->message = [
			'NO_DATA_DEFINED' => 'You must set the data first before update the instance',
			'SAVE_FAILED' => 'Failed to save the data',
			'UNKNOWN_FIELD' => 'Unknown field name {field} in update lists',
		];
	}

	public function setModel($initial=''){
		$this->model = app(config('model.'.$initial));
		return $this;
	}

	public function all($order_by='id', $order_dir='ASC', $ignore_is_active=false, $is_active_field='is_active'){
		if($ignore_is_active){
			return $this->model->orderBy($order_by, $order_dir)->get();
		}
		else{
			return $this->model->where($is_active_field, 1)->orderBy($order_by, $order_dir)->get();
		}
	}

	public function paginate($per_page=10, $ignore_is_active=false, $is_active_field='is_active'){
		if($ignore_is_active){
			return $this->model->paginate($per_page);
		}
		else{
			return $this->model->where($is_active_field, 1)->paginate($per_page);
		}
	}

	public function filter($rule=[]){
		return (new CrudRepository($this->initial))->filter($filter);
	}

	public function get($id, $pk='id'){
		return (new CrudRepository($this->initial))->show($id, $pk);
	}


	public function setData($var){
		if($var instanceof Model){
			$this->data = $var;
		}
		else if(is_numeric($var)){
			$this->data = $this->model->find($var);
		}

		return $this;
	}


	public function insert($param=[], $strict=false){
		//proses insert dan update itu sebenarnya sama.. hanya saja insert membuat variabel $data jd kosong dulu
		$this->data = $this->model;
		$this->update($param, $strict);
	}


	//method dibawah ini by default hanya boleh dipakai kalau ada $this->data
	public function update($param=[], $strict=false){
		if($this->data){
			$listing = $this->modelTableListing();

			foreach($param as $field => $value){
				if(in_array($field, $listing)){
					$this->data->{$field} = $value;
				}
				else{
					//bisa throw exception juga kalo mau
					if($strict){
						throw new InstanceException($this->getMessage('UNKNOWN_FIELD', ['field' => $field]));
					}
				}
			}
			try{
				$this->data->save();
			}catch(\Exception $e){
				throw new InstanceException($this->getMessage('SAVE_FAILED'));
			}
			return $this;
		}
		else{
			throw new InstanceException($this->getMessage('NO_DATA_DEFINED'));
		}
	}

	public function delete($strict=false, $is_active_field='is_active'){
		if($this->data){
			if($strict){
				//hard delete
				$this->data->delete();
				$this->data = null;
			}
			else{
				//set is active to 9
				$this->data->update([
					$is_active_field => 9
				]);
			}
			return $this;
		}
		else{
			throw new InstanceException($this->message['NO_DATA_DEFINED']);
		}
	}

	protected function modelTableListing(){
        return $this->model->getConnection()->getSchemaBuilder()->getColumnListing($this->model->getTable());
	}

	public function getMessage($name, $var=[]){
		if(isset($this->message[$name])){
			$msg = $this->message[$name];

			if(!empty($var)){
				foreach($var as $key => $alias){
					$msg = str_replace('{'.$key.'}', $alias, $msg);
				}
			}

			return $msg;
		}
		return null;
	}

}