<?php
$base_class = ['form-control', 'select2'];
if(isset($attr['class'])){
  $class = $attr['class'];
}
if(isset($class)){
  $base_class = array_merge($base_class, $class);
}

$cleaned_name = str_replace('[]', '', $name);
$value = old($cleaned_name, isset($value) ? $value : null);
$type = isset($type) ? $type : 'select';

if(is_array($source)){
  $data_source = $source;
}
else{
  $data_source = isset($source->output) ? $source->output : $source;
  if(is_callable($data_source)){
    $data_source = call_user_func($data_source, $data);
  }
}

if($type == 'select_multiple' && strpos($name, '[]') === false){
  $name = $name.'[]';
}

if(!isset($multi_language)){
  $multi_language = false;
}
if($multi_language){
  if(strpos($name, '[]') !== false){
    $name = str_replace('[]', '['.def_lang().'][]', $name);
  }
  else{
    $name = $name.'['.def_lang().']';
  }
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = $value[def_lang()];
  }
}

if($value instanceof \Illuminate\Support\Collection){
  $value = old($cleaned_name, $value->toArray());
}
?>

<select {{ $type == 'select_multiple' ? 'multiple' : '' }} name="{!! $name !!}" class="{!! implode(' ', $base_class) !!}" {!! isset($attr) ? array_to_html_prop($attr, ['class', 'type', 'name', 'id']) : null !!}>
  @if($type == 'select')
  <option value=""></option>
  @endif
  @foreach($data_source as $key => $vl)
  <option {{ is_array($value) ? (in_array($key, $value) ? 'selected' : '') : ($key == $value ? 'selected' : null) }} value="{{ $key }}">{{ $vl }}</option>
  @endforeach
</select>
