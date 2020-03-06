<?php
if(isset($multi_language)){
  $name = $name.'['.def_lang().']';
}
if(!isset($value)){
  $value = null;
}

$config = [];
if(isset($path)){
  $config['path'] = $path;
}
?>
<div>
{!! MediaInstance::input($name, $value, $config) !!}
</div>
