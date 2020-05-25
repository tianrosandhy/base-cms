<?php
if(isset($class)){
  $base_class = $class;
}

$cleaned_name = str_replace('[]', '', $name);
if(!isset($multi_language)){
  $multi_language = false;
}
if($multi_language){
  $name = $name.'['.def_lang().']';
}

if(is_array($source)){
  $data_source = $source;
}
else{
  $data_source = isset($source->output) ? $source->output : $source;
  if(is_callable($data_source)){
    $data_source = call_user_func($data_source, $data);
  }
}

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = old($cleaned_name.'.'.def_lang(), $value[def_lang()]);
  }
}
else{
  $value = old($cleaned_name, $value);
}
?>
<div class="box">
  @foreach($data_source as $vl => $lbl)
  <label class="radio-inline">
    <input type="{{ isset($type) ? $type : 'radio' }}" value="{{ $vl }}" name="{!! $name !!}" id="input-{{ $cleaned_name }}-{{ slugify($lbl) }}" {{ $value == $vl ? 'checked' : '' }}>
    <span>{{ $lbl }}</span>
  </label>
  @endforeach
</div>

