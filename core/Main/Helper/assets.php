<?php
function fe_asset($path){
  $separator = '/';
  if(substr($path, 0, 1) == '/'){
    $separator = '';
  }
  return url(config('cms.front.assets') . $separator . $path);
}

function fe_asset_mix($path){
  $separator = '/';
  if(substr($path, 0, 1) == '/'){
    $separator = '';
  }

  return asset(mix(config('cms.front.assets') . $separator . $path));
}

function admin_asset($path){
  return asset(config('cms.admin.assets') . '/'. $path);
}
