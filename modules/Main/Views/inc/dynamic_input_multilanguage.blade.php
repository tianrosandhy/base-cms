<?php
$default = old(str_replace('[]', '', $row->field), '');
if(!isset($default[def_lang()])){
	$default = [];
	$default[def_lang()] = '';
}

if($row->value_source){
	$default[def_lang()] = FormService::getDefaultValue($row->value_source, (isset($data->id) ? $data->id : 0));
}
if($row->array_source){
	if(!isset($data)){
		$data = null;
	}

	$default[def_lang()] = (call_user_func($row->array_source, $data));
}
elseif($row->value_data){
	$fn = $row->value_data;
	$default[def_lang()] = $fn($data);
}
else{
	if(isset($data)){
		if(method_exists($data, 'outputTranslate')){
			foreach(available_lang(true) as $lang){
				$default[$lang] = $data->outputTranslate($row->field, $lang, true);
			}
		}
		else{
			//row->field harus dibuat []nya kalo ada
			$fldnm = str_replace('[]', '', $row->field);
			$default[def_lang()] = (isset($data->{$fldnm}) ? $data->{$fldnm} : '');
		}
	}
	else{
		$data = null;
	}
}
?>
{!! FormService::inputMultilang($row, $default, $data) !!}