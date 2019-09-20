<?php
namespace Module\Main\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Module\Main\Http\Repository\CrudRepository;

class DataSource
{

	private 
		$model;
	public
		$output;

	public function __construct(Application $app){
		$this->output = [];
	}

	public function model($model){
		//inputan bisa berupa class model langsung, maupun initial class
		$this->model = new CrudRepository($model);
		return $this;
	}

	public function options($shown='', $filter=[], $pk='id'){
		$data = $this->model->filter($filter);
		$out = [];
		foreach($data as $row){
			if(isset($row->{$pk}) && isset($row->{$shown})){
				$out[$row->{$pk}] = $row->{$shown};
			}
		}
		$this->output = $out;
		return $this;
	}

	public function setList($array=[]){
		$this->output = $array;
		return $this->output;
	}

	public function output(){
		return $this->output;
	}

	public function customHandler(\Closure $callback){
		$this->output = $callback();
		return $callback();
	}


}