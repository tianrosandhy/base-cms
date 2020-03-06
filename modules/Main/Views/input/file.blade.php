<?php
if(isset($multi_language)){
  $name = $name.'['.def_lang().']';
}
if(!isset($value)){
  $value = null;
}

$config = [
  'value' => (isset($value) ? $value : null),
  'name' => $name,
  'horizontal' => true
];

if(isset($path)){
  $config['path'] = $path;
}

$view_source = 'main::inc.file-dropzone';
if($type == 'file_multiple'){
  $view_source = 'main::inc.file-dropzone-multiple';
}
?>
<div>
  @include($view_source, $config)
</div>
