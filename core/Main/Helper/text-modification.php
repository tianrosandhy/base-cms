<?php

function slugify($input, $delimiter='-'){
  $string = strtolower(str_replace(' ', $delimiter, $input));
  if(strpos($string, '&') !== false){
    $string = str_replace('&', 'and', $string);
  }
  return $string;
}

function prettify($slug, $delimiter='-'){
  return str_replace($delimiter, ' ', $slug);
}

function tagToHtml($tags, $label_class='label-default'){
  $out = explode(',', $tags);
  $html = '';
  foreach($out as $item){
    $html .= '<span class="label '.$label_class.'">'.trim($item).'</span> ';
  }
  return $html;
}

function descriptionMaker($txt, $length=30){
  $txt = strip_tags($txt);
  $pch = explode(' ', $txt);
  $out = '';
  for($i=0; $i<$length; $i++){
    if(isset($pch[$i])){
      $out .= $pch[$i].' ';
    }
  }

  if(count($pch) > $length){
    $out .= '...';
  }

  return $out;
}

function random_color() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function array_to_html_prop($arr=[], $ignore_key=[]){
  if(empty($arr)){
    return '';
  }
  $out = '';
  foreach($arr as $key => $value){
    if(is_array($value)){
      $value = implode(' ',$value);
    }
    elseif(is_object($value)){
      $value = json_encode($value);
    }

    if(in_array(strtolower($key), $ignore_key)){
      continue;
    }

    $out.= $key.'="'.$value.'" ';
  }

  return $out;
}
