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
if(!isset($value)){
  $value = null;
}

$config = [];
if(isset($path)){
  $config['path'] = $path;
}
?>
<div>
{!! MediaInstance::inputMultiple($name, $value, $config) !!}
</div>
