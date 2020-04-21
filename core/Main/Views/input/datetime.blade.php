<?php
$base_class = ['form-control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);
$old_name = $cleaned_name;
if(!isset($multi_language)){
  $multi_language = false;
}

if($multi_language){
  $name = $name.'['.def_lang().']';
  $old_name = $cleaned_name.'.'.def_lang();
}


if($type == 'date'){
  $mask = '0000-00-00';
  $attr['data-datepicker'] = 1;
}
elseif($type == 'time'){
  $mask = '00:00';
  $attr['data-timepicker'] = 1;
}
else{
  $mask = '0000-00-00 00:00:00';
  $attr['data-datetimepicker'] = 1;
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = $value[def_lang()];
  }
}
?>
<input name="{!! $name !!}" type="text" data-mask="{{ $mask }}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} id="input-{{ $cleaned_name }}" value="{{ old($old_name, (isset($value) ? $value : null)) }}">
