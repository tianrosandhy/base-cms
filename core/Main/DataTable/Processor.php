<?php
namespace Core\Main\DataTable;

use Illuminate\Http\Request;
use Validator;
use Core\Main\DataTable\DataTable;
use Core\Main\Http\Repository\CrudRepository;
use Core\Main\Exceptions\DataTableException;

class Processor
{
	public 
		$model,
		$model_with,
		$data_table,
		$field_definition,
		$draw,
		$columns,
		$order,
		$start,
		$length,

		$query_count,
		$raw_data,
		$output;

	public function __construct(){
		$this->request = request();
	}

	public function table(){
		try{
			$this->setDataTable();
			$this->validateRequest();
			$this->process();
			$this->tableFormat();
			return $this->getResponse();
		}catch(DataTableException $e){
			return $this->errorResponse($e);
		}catch(\Exception $e){
			return $this->errorResponse($e);
		}
	}

	private function errorResponse($e){
		return [
			'error' => $e instanceof DataTableException ? $e->getMessage() : 'Server Error. We cannot process your request right now',
			'detail' => $e instanceof DataTableException ? null : (method_exists($e, 'getMessage') ? $e->getMessage() : null)
		];
	}

	private function throwErr($msg){
		throw new DataTableException($msg);
	}

	public function tableFormat(){
		$out = [];
		foreach($this->raw_data as $row){
            $rf = $this->rowFormat($row);
            if(!empty($rf)){
            	$tmp = [];
            	$i = 0;

            	$arval = array_values($rf);
            	if(strpos($arval[0], 'multichecker_datatable') !== false){
            		$btn_pos = 1;
            	}
            	else{
            		$btn_pos = 0;
            	}

            	foreach($rf as $key => $value){
            		if($key == 'action'){
            			//skipped
            			continue;
            			$tmp[$key] = '';
            		}
        			$tmp[$key] = $value;
            		if($i == $btn_pos){ //action button selalu dimunculkan di kolom kedua
            			if(isset($rf['action'])){
                    if(strlen(trim($rf['action'])) > 0){
                        $tmp[$key] .= '<div class="action-buttons" style="min-width:250px;">'.$rf['action'].'</div>';
                    }
            			}
            		}
            		$i++;
            	}
                $out[] = $tmp;
            }
		}

		$this->output = $out;
	}

	public function rowFormat($row, $as_excel=false){
		return []; //default use kalo pake format lama : ga mengembalikan nilai apa2
	}

	public function switcher($row, $field='is_active', $url='post/switch', $pk='id'){
		if(strpos($url, '.') !== false){
			try{
				$url = url()->route($url);
			}catch(\Exception $e){
				$url = admin_url($url);
			}
		}
		else{
			$url = admin_url($url);
		}

		return view('main::inc.switchery', [
			'id' => $row->{$pk}, 
			'field' => $field,
			'url' => $url,
			'value' => $row->{$field}
		])->render();
	}

	public function checkerFormat($row, $pk='id'){
		return '<input type="checkbox" data-id="'.$row->{$pk}.'" name="multi_check['.$row->{$pk}.']" class="multichecker_datatable"><span style="color:transparent; position:absolute;">'.$row->{$pk}.'</span>';
	}

	public function validateRequest(){
		//prepare variabel disini aja sekalian
		$this->draw = $this->request->draw;
		$this->columns = $this->request->columns;
		$this->order = $this->request->order;
		$this->start = $this->request->start;
		$this->length = $this->request->length;

		$validator = Validator::make($this->request->all(), [
			'draw' => 'required|numeric',
			'columns' => 'required|array',
			'order' => 'array',
			'start' => 'required',
			'length' => 'required',
		]);

		if($validator->fails()){
			$this->throwErr('Invalid datatable request');
		}
	}

	public function setModel($model){
		//inputan bisa berupa class model langsung, maupun initial class
		try{
			if($model instanceof Model){
				$this->model = $model;
			}
			else{
				$this->model = model($model);
			}
		}catch(DataTableException $e){
			$this->throwErr('Cannot find the model instance for this datatable');
		}
	}

	public function setModelWith(){
		$this->model_with = func_get_args();
	}

	public function setDataTable(){
		$i = 0;
		foreach($this->structure as $row){
			if(!$row->hide_table){
				$this->field_definition[$i] = $row->field;
				$i++;
			}
		}
	}


	public function process(){
		//prepare filter data by search query
		$filter = [];
		foreach($this->getSearchArray() as $idfield => $list){
			if(isset($this->columns[$idfield]['search']['value'])){
				$filteredString = $this->columns[$idfield]['search']['value'];
			}
			else{
				continue;
			}
			if($list['type'] == 'text'){
				//ada sedikit masalah dgn php : simbol % kalau diikutin angka, ntar jadi URL decoded string.
				//jadi kalo $filteredString ada angka didepannya, dgn terpaksa % di awalnya harus dihilangin -_-
                if(in_array($list['input_type'], ['date', 'datetime'])){
					$default_time = date('Y-m-d');
					if(strlen(trim($filteredString)) > 1){
						$pch = explode('|', $filteredString);
						if(count($pch) == 2){
                            if($list['input_type'] == 'date'){
                                $atime = $pch[0];
                                $btime = $pch[1];
                            }
                            else{
                                $atime = $pch[0].' 00:00:00';
                                $btime = $pch[1].' 23:59:59';
                            }
                            
							if(strlen($atime) > 0 && strlen($btime) > 0){
								$filter[] = [$list['field'], 'between('.$atime.'|'.$btime.')'];
							}
							elseif(strlen($atime) == 0){
								$filter[] = [$list['field'], 'lte('.$btime.')'];
							}
							elseif(strlen($btime) == 0){
								$filter[] = [$list['field'], 'bte('.$atime.')'];
							}
						}
					}
				}
				else{
					if(is_numeric(substr($filteredString, 0, 1))){
						$fs = $filteredString . '%';
					}
					else{
						$fs = '%' . $filteredString . '%';
					}
					$filter[] = [$list['field'], 'like', $fs];
				}

			}
			else{
				//utk custom field type (combobox)
				$filter[] = [$list['field'], '=', $filteredString];
			}
		}

		$listing = (new CrudRepository($this->model))->modelTableListing();
		if(empty($listing)){
			$this->throwErr('Invalid model or table instance');
		}
		$orderBy = isset($listing[0]) ? $listing[0] : null; //asumsi kolom pertama itu primary key
		$flow = 'DESC';
		if(isset($this->field_definition[$this->request->order[0]['column']])){
			$orderBy = $this->field_definition[$this->request->order[0]['column']];
		}
		if(!in_array($orderBy, $listing)){
			$orderBy = $listing[0]; //balik order by id kalo ternyata kolom tsb gaisa disort
		}


		if(isset($this->request->order[0]['dir'])){
			$flow = $this->request->order[0]['dir'];
		}

		//gausa pake crud repository
		$ctx = $this->model;
		$result = (new CrudRepository($ctx));
		if($this->model_with){
			$result = $result->with($this->model_with);
		}
		$result = $result->paramManagement($ctx, $filter);
		$result = $this->additionalSearchFilter($ctx);

		if($result instanceof \Illuminate\Database\Eloquent\Builder){
			$this->query_count = $result->get()->count();
		}
		else{
			$this->query_count = $result->count();
		}
		$this->raw_data = $result->orderBy($orderBy, $flow)->skip($this->start)->take($this->length)->get();
	}

	public function additionalSearchFilter($context){
		return $context;
	}

	public function grabColumn($name){
		$out = [];
		$posts = $this->request->all();
		if(isset($posts['columns'])){
			foreach($posts['columns'] as $row){
				if(isset($row['search']['value']))
				$out[$row['data']] = $row['search']['value'];
			}
		}

		if(isset($out[$name])){
			return $out[$name];
		}
		return false;
	}

	public function manageFilter($old_filter=[]){
		//diolah lagi secara custom jika diperlukan
		return $old_filter;
	}


	public function getResponse(){
		return [
			'draw' => $this->request->draw,
			'data' => $this->output,
			'recordsFiltered' => $this->query_count,
			'recordsTotal' => $this->query_count
		];
	}



}