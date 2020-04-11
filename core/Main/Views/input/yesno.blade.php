<?php
$hash = sha1(rand(1, 10000) . time());

if(!isset($multi_language)){
  $multi_language = false;
}
if($multi_language){
  $name = $name.'['.def_lang().']';
  $value = isset($value[def_lang()]) ? $value[def_lang()] : $value;
}
if(!isset($value)){
  $value = null;
}

$value = old($name, isset($value) ? (bool)$value : false);
?>
<div style="padding:.5em">
	<input type="hidden" name="{{ $name }}" id="yesno_{{ $hash }}" value="{{ $value ? 1 : 0 }}">
	<input type="checkbox" yesno data-target="#yesno_{{ $hash }}" data-size="small" class="js-switch" value="1" {{ $value == 1 ? 'checked' : '' }}>	
</div>
