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

//mencegah value multiple language. this input doesnt expect array value
if(is_array($value)){
  if(array_key_exists(def_lang(), $value)){
    $value = $value[def_lang()];
  }
}
?>
<div class="box">
  @foreach($source as $vl => $lbl)
  <label class="radio-inline">
    <input type="{{ isset($type) ? $type : 'radio' }}" value="{{ $vl }}" name="{!! $name !!}" id="input-{{ $cleaned_name }}-{{ slugify($lbl) }}" {{ $value == $vl ? 'checked' : '' }}>
    <span>{{ $lbl }}</span>
  </label>
  @endforeach
</div>

