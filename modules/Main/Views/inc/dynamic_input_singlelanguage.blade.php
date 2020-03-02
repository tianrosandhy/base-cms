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
elseif($row->value_data){
	$fn = $row->value_data;
	$default = $fn($data);
}
else{
	if(isset($data)){
		if(method_exists($data, 'outputTranslate')){
			foreach(available_lang(true) as $lang => $langdata){
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

//in case default is empty, back to default value from old()
if(empty($default)){
    $default = old(str_replace('[]', '', $row->field), null);
}
?>

{!! FormService::input($row, $default, $data) !!}
