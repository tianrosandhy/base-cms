<?php
if(isset($class)){
  $base_class = $class;
}

$cleaned_name = str_replace('[]', '', $name);
if(isset($multi_language)){
  $name = $name.'['.def_lang().']';
}

$value = isset($value) ? $value : 0;
?>
<div class="box">
  @foreach($source as $vl => $lbl)
  <label class="radio-inline">
    <input type="{{ isset($type) ? $type : 'radio' }}" value="{{ $vl }}" name="{!! $name !!}" id="input-{{ $cleaned_name }}-{{ slugify($lbl) }}" {{ $value == $vl ? 'checked' : '' }}>
    <span>{{ $lbl }}</span>
  </label>
  @endforeach
</div>

