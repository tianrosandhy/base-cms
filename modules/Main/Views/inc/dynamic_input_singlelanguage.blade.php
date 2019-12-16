<?php
$default = old(str_replace('[]', '', $row->field), null);
if(!isset($default)){
	$default = null;
}
if($row->value_source){
	$default = FormService::getDefaultValue($row->value_source, (isset($data->id) ? $data->id : 0));
}
if($row->array_source){
	if(!isset($data)){
		$data = null;
	}
	
	$default = (call_user_func($row->array_source, $data));
}
else{
	if(isset($data)){
		if(method_exists($data, 'outputTranslate')){
			foreach(available_lang(true) as $lang){
				$default = $data->outputTranslate($row->field, $lang, true);
			}
		}
		else{
			//row->field harus dibuat []nya kalo ada
			$fldnm = str_replace('[]', '', $row->field);
			$default = (isset($data->{$fldnm}) ? $data->{$fldnm} : '');
		}
	}
	else{
		$data = null;
	}
}
?>

{!! FormService::input($row, $default, $data) !!}
