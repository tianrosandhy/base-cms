<?php
$base_class = ['form_control'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);
if(!isset($multi_language)){
  $multi_language = false;
}

if($multi_language){
  $name = $name.'['.def_lang().']';
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
?>
<input type="text" data-mask="{{ $mask }}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!} id="input-{{ $cleaned_name }}" value="{{ isset($value) ? $value : null }}">
