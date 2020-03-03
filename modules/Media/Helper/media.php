<?php
function allImageSet($url){
  $files = [];
  $list = ['origin'];
  $list = array_merge($list, array_keys(config('image.thumbs')));
  foreach($list as $ls){
    $ts = thumbnailSet($url, $ls, false);
    if(isset($ts['normal'])){
      $files[$ls] = $ts['normal'];
    }
    if(isset($ts['webp'])){
      $files[$ls.'-webp'] = $ts['webp'];
    }
  }
  return $files;
}

function getOriginThumbnail($thumb_url, $mode='thumb'){
  $real_url = str_replace('-'.$mode, '', $thumb_url);
  //pengecekan dari thumb -> origin sebenernya banyak. kapan2 boleh ditambah kalo error

  return $real_url;
}

function thumbnailSet($url, $get='', $fallback=true){
  $extension = getExtension($url);
  $filename = str_replace('.'.$extension, '', $url);

  if($get == 'origin'){
    $grabbed_file = $filename.'.'.$extension;
    $grabbed_webp = $filename.'.webp';
  }
  else{
    $grabbed_file = $filename.'-'.$get.'.'.$extension;
    $grabbed_webp = $filename.'-'.$get.'.webp';
  }

  $out = [];
  if(ImageService::urlExists($grabbed_webp)){
    $out['webp'] = $grabbed_webp;
  }
  else{
    if(ImageService::pathExists($grabbed_webp)){
      $out['webp'] = $grabbed_webp;
    }
  }

  if(ImageService::urlExists($grabbed_file)){
    $out['normal'] = $grabbed_file;
  }
  else{
    if(ImageService::pathExists($grabbed_file)){
      $out['normal'] = $grabbed_file;
    }
    else{
      if($fallback){
        $out['normal'] = $url;
      }
    }
  }

  return $out;
}

function thumbnail($url, $get='', $fallback=true){
  $thumbs = config('image.thumbs');

  $pch = explode(".", $url);
  $extension = $pch[(count($pch)-1)];
  $filename = str_replace('.'.$extension, '', $url);

  if(ImageService::urlExists($url)){
    $out['origin'] = $url;
  }
  else{
    if(ImageService::pathExists($url)){
      $out['origin'] = $url;
    }
    else{
      $out['origin'] = false;
    }
  }

  foreach($thumbs as $alias => $size){
    $hmm = $filename . '-'.$alias.'.'.$extension;
    if(ImageService::urlExists($hmm)){
      $out[$alias] = $hmm;
    }
    else{
      if(ImageService::pathExists($hmm)){
        $out[$alias] = $hmm;
      }
      else{
        if($fallback){
          $out[$alias] = $out['origin'];
        }
        else{
          $out[$alias] = false;
        }
      }
    }
  }


  if(isset($out[$get])){
    return $out[$get];
  }
  return $out;
}

function broken_image(){
  return asset('admin_theme/img/broken-image.jpg');
}
