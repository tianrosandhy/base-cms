<?php
namespace Module\Main\DataTable;

use Illuminate\Database\Eloquent\Model;
use Module\Main\DataTable\Processor;
use Illuminate\Http\Request;
use Validator;
use CMS;

class DataTable extends Processor
{
	public $structure;

	public function __construct(Request $req){
		$this->request = $req;
	}

	public function view(){
		return view('main::inc.datatable', [
			'structure' => $this->structure
		])->render();
	}

	public function getSearchArray(){
		$out = [];
		$i = 0;
		foreach($this->structure as $row){
			if($row->hide_table == false){
				if($row->searchable){
					$out[$i]['field'] = $row->field;
					$out[$i]['input_type'] = $row->input_type;
					$out[$i]['type'] = $row->data_source == 'text' ? 'text' : 'multiple';
					$out[$i]['value'] = isset($this->request->columns[$i]['search']['value']) ? $this->request->columns[$i]['search']['value'] : null;
				}
				$i++;
			}
		}
		return $out;
	}

	public function grabFieldValue($fieldname){
		$search_array = collect($this->getSearchArray());
		$grab = $search_array->where('field', $fieldname);
		if($grab->count() > 0){
			$grab = $grab->first();
		}

		if(isset($grab['value'])){
			return $grab['value'];
		}
		return null;
	}

	public function actionButton($title='', $url, $attr=[]){
		$attr = CMS::combineAttribute($attr);
		return '<a href="'.$url.'" '.$attr.'>'.$title.'</a> ';
	}




	//DYNAMIC JS OUTPUT UTK SEARCH, ORDERABLE, NAME

	public function searchQuery(){
		$out = '';
		$i = 0;
		foreach($this->structure as $row){
			if($row->hide_table == false){
				$fld = str_replace('[]', '', $row->field);
				if($row->input_type == 'date'){
					$out .= 'data.columns['.$i.']["search"]["value"] = $("#datatable-search-'.$fld.'-1").val() + "|" + $("#datatable-search-'.$fld.'-2").val(), ';
				}
				else{
					$out .= 'data.columns['.$i.']["search"]["value"] = $("#datatable-search-'.$fld.'").val(), ';
				}
				$i++;
			}
		}
		return $out;
	}

	public function orderable(){
		$out = '';
		$i = 0;
		foreach($this->structure as $row){
			if($row->hide_table == false){
				if(!$row->orderable){
					$out .= "{'targets' : ".$i.", 'orderable' : false}, ";
				}
				$i++;
			}
		}

		$out .= "{'targets' : ".$i.", 'orderable' : false}";
		return $out;
	}

	public function columns(){
		$i = 0;
		$out = '';
		foreach($this->structure as $row){
			if($row->hide_table == false){
				$fld = str_replace('[]', '', $row->field);
				$out .= "{data : '".$fld."'}, ";
			}
		}
		$out .= "{data : 'action'}";
		return $out;
	}




	//UNTUK FORM VALIDATION
	public function formValidation($multi_language=false, $type='create', $id=0){
		if($type == 'create'){
			$get = 'create_validation';
		}
		else{
			$get = 'update_validation';
		}

		$validateData = [];
		foreach($this->structure as $row){
			if(strlen($row->{$get}) > 0){
				$rules = str_replace('[id]', $id, $row->{$get});
				$field = str_replace('[]', '', $row->field);
				if($multi_language){
					$validateData[$field.'.'.config('cms.lang.default')] = $rules;
				}
				else{
					$validateData[$field] = $rules;
				}
			}
		}

		if(count($validateData) == 0)
			return false; //auto lewat kalo ga ada validasi

		return Validator::make($this->request->all(), $validateData)->validate();
	}


	public function getActiveFormFields(){
		$out = [];
		foreach($this->structure as $str){
			if(!$str->hide_form && !$str->input_array){
				$out[] = $str->field;
			}
		}
		return $out;
	}
}