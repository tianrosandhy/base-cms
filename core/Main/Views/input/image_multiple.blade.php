<?php
if(strpos($name, '[]') === false){
  $name = $name.'[]';
}
if(!isset($multi_language)){
  $multi_language = false;
}
if($multi_language){
  $name = $name.'['.def_lang().']';
}

$cleaned_name = str_replace('[]', '', $name);
if(!isset($value)){
  $value = old($cleaned_name);
}

$config = [];
if(isset($path)){
  $config['path'] = $path;
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
	if(array_key_exists(def_lang(), $value)){
		$value = $value[def_lang()];
	}
}
?>
<div>
{!! MediaInstance::inputMultiple($name, $value, $config) !!}
</div>
