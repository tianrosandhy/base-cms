<?php
$attr = isset($attr) ? $attr : [];
if(strpos($name, '[]') === false){
  $name = $name.'[]';
}

if(is_string($value)){
  $value = json_decode( $value, true );
}

if(empty($value)){
  $value = [''];
}
?>
<div class="input-multiple-holder">
  <div class="card">
    <div class="card-body input-multiple-container">
      @foreach($value as $val)
        {!! MediaInstance::input($name, $val, $attr) !!}
      @endforeach
    </div>
    <div class="card-footer">
      <a href="#" class="btn btn-secondary btn-block btn-add-images">Add Images</a>
    </div>
  </div>
</div>
<template id="single-input">
  {!! MediaInstance::input($name) !!}
</template>