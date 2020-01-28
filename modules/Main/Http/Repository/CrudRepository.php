<?php
namespace Module\Main\Http\Repository;

use Illuminate\Database\Eloquent\Model;

class CrudRepository{
	public $model;

	public function __construct($model){
		//inputan bisa berupa class model langsung, maupun initial class
		if($model instanceof Model){
			$this->model = $model;
		}
		else{
			$this->model = app(config('model.'.$model));
		}
	}

	public function getModel(){
		return $this->model;
	}

	public function with($args){
		$this->model = $this->model->with($args);
		return $this;
	}

	public function all(){
		return $this->model->get();
	}

	public function show($input, $by='id'){
		$model = $this->model;

		//get translate scope if exists
		if(method_exists($model, 'scopeGetTranslate')){
			$model = $model->with('translate');
		}

		return $model
			->where($by, $input)
			->first();
	}

	public function filter($param=[], $orderBy='id', $skip=0, $take=0, $flow='DESC'){
		$data = $this->model;
		if(count($param) > 0){
			$data = $this->paramManagement($data, $param);
		}
		$data = $data->orderBy($orderBy, $flow);
		if($take > 0){
			$data = $data->skip($skip);
			$data = $data->take($take);
		}
		return $data->get();
	}

	public function modelTableListing(){
		$model = $this->model;
        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
	}




	//shortcode get active only data
	public function filterActive($additional=[], $orderBy='id', $skip=0, $take=0, $flow='DESC'){
		$real_filter = [
			['is_active', '=', 1]
		];
		if(count($additional) > 0){
			foreach($additional as $add){
				$real_filter[] = $add;
			}
		}
		return $this->filter($real_filter, $orderBy, $skip, $take, $flow);
	}

	public function paramManagement($obj, $param=[]){
		$listing = $this->modelTableListing();
		foreach($param as $key => $prm){
			if(!is_array($prm)){
				if(in_array($key, $listing)){
					$obj = $this->operatorManagement($obj, $key, $prm);
				}
			}
			elseif(count($prm) == 2){
				$prm = array_values($prm);
				if(in_array($prm[0], $listing)){
					$obj = $this->operatorManagement($obj, $prm[0], $prm[1]);
				}
			}
			elseif(count($prm) == 3){
				$prm = array_values($prm);
				if(in_array($prm[0], $listing)){
					$obj = $obj->where($prm[0], $prm[1], $prm[2]);
				}
			}
		}
		return $obj;
	}

	public function operatorManagement($output, $field, $value){
		//append nama tabelnya biar SQL valid
		$field = $output->getModel()->getTable() . '.'.$field;

		if(strpos($value, 'like(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, 'like', '%'.$value.'%');
		}
		elseif(strpos($value, 'endlike(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, 'like', $value.'%');
		}
		elseif(strpos($value, 'startlike(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, 'like', '%'.$value);
		}
		elseif(strpos($value, 'not(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, '<>', $value);
		}
		elseif(strpos($value, 'bt(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, '>', $value);
		}
		elseif(strpos($value, 'lt(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, '<', $value);
		}
		elseif(strpos($value, 'bte(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, '>=', $value);
		}
		elseif(strpos($value, 'lte(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$output = $output->where($field, '<=', $value);
		}
		elseif(strpos($value, 'between(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$exploded_value = explode('|', $value);
			if(count($exploded_value) == 2){
				if(strtotime($exploded_value[0]) && strtotime($exploded_value[1])){
                    $used_format = 'Y-m-d';
                    if(strpos($exploded_value[0], ' ') !== false){
                        $used_format = 'Y-m-d H:i:s';
                    }

					$output = $output->whereBetween($field, [
						date($used_format, strtotime($exploded_value[0])),
						date($used_format, strtotime($exploded_value[1])),
					]);
				}
				else{
					$output = $output->whereBetween($field, $exploded_value);
				}
			}
		}
		elseif(strpos($value, 'notbetween(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$exploded_value = explode('|', $value);
			if(count($exploded_value) == 2){
				if(strtotime($exploded_value[0]) && strtotime($exploded_value[1])){
					$used_format = 'Y-m-d';
                    if(strpos($exploded_value[0], ' ') !== false){
                        $used_format = 'Y-m-d H:i:s';
                    }

					$output = $output->whereBetween($field, [
						date($used_format, strtotime($exploded_value[0])),
						date($used_format, strtotime($exploded_value[1])),
					]);
				}
				else{
					$output = $output->whereNotBetween($field, $exploded_value);
				}
			}
		}
		elseif(strpos($value, 'in(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$vlists = explode('|', $value);
			$output = $output->whereIn($field, $vlists);
		}
		elseif(strpos($value, 'notin(') !== false){
			$value = $this->getStringBetween($value, '(', ')');
			$vlists = explode('|', $value);
			$output = $output->whereNotIn($field, $vlists);
		}
		elseif($value == '(null)'){
			$output = $output->whereNull($field);
		}
		else{
			$output = $output->where($field, $value);
		}
		return $output;
	}

	public function getStringBetween($str,$from,$to, $withFromAndTo = false){
       $sub = substr($str, strpos($str,$from)+strlen($from),strlen($str));
       if ($withFromAndTo)
         return $from . substr($sub,0, strrpos($sub,$to)) . $to;
       else
         return substr($sub,0, strrpos($sub,$to));
    }



	public function filterPaginate($param=[], $orderBy='id', $flow='DESC', $per_page=10){
		$data = $this->model;
		if(count($param) > 0){
			$data = $this->paramManagement($data, $param);
		}
		$data = $data->orderBy($orderBy, $flow);
		return $data->paginate($per_page);
	}

	public function filterFirst($param=[], $orderBy='id'){
		return $this->filter($param, $orderBy)->first();
	}

	public function filterDelete($param=[]){
		$data = $this->model;
		if(count($param) > 0){
			$data = $this->paramManagement($data, $param);
		}
		return $data->delete();
	}

	public function insert($param){
		$data = $this->model;
		foreach($param as $field=>$val){
			$data->$field = $val;
		}
		$data->save();
		return $data;
	}

	public function update($id, $param){
		$instance = $this->model->find($id);
		foreach($param as $fld => $val){
			$instance->$fld = $val;
		}
		$instance->save();
		return $instance;
	}

	public function delete($id){
		if(!is_array($id)){
			$id = [$id];
		}
		return $this->model->whereIn('id', $id)->delete();
	}

	public function deleteWhere($field='id', $val=0){
		return $this->model->where($field, $val)->delete();
	}



	public function search($keyword='', $field=['title'], $isactive=false, $isactive_field='is_active'){
		$n = 0;
		$filter = $this->model;

		if($isactive){
			$filter = $filter->where($isactive_field, 1);
		}

		foreach($field as $fld){
			$pecah = explode("-", $keyword);
			foreach($pecah as $pch){
				if($n == 0){
					$filter = $filter->where($fld, 'like', '%'.$pch.'%');
				}
				else{
					$filter = $filter->orWhere($fld, 'like', '%'.$pch.'%');
				}
				$n++;
			}
		}

		return $filter->get();
	}

	public function searchPaginate($keyword='', $field=['title'], $per_page=10, $flow='desc'){
		$n = 0;
		$filter = $this->model;

		$filter = $filter->where('is_active', 1);

		foreach($field as $fld){
			$pecah = explode("-", $keyword);
			foreach($pecah as $pch){
				if($n == 0){
					$filter = $filter->where($fld, 'like', '%'.$pch.'%');
				}
				else{
					$filter = $filter->orWhere($fld, 'like', '%'.$pch.'%');
				}
				$n++;
			}
		}

		return $filter->orderBy('id', $flow)->paginate($per_page);
	}

	public function fullSearch($keyword='', $field=['title']){
		$key = '%'.str_replace('-', '%', $keyword);
		$n=0;
		$filter = $this->model;
		foreach($field as $fld){
			if($n == 0){
				$filter = $filter->where($fld, 'like', '%'.$key.'%');
			}
			else{
				$filter = $filter->orWhere($fld, 'like', '%'.$key.'%');
			}
			$n++;
		}
		return $filter->get();
	}


	public function exists($id){
		$data = $this->show($id);
		if(!empty($data))
			return true;
		return false;
	}

}