<?php

function set_lang($lang='en'){
  $default = def_lang();
  $list = LanguageInstance::available();
  if(!array_key_exists($lang, $list)){
    $lang = $default;
  }
  session()->put('lang', $lang);
}

function current_lang(){
  $lang = session('lang');
  if(empty($lang)){
    //cek header Accept-Language kalau ada
    $headers = Request::header();
    if(isset($headers['accept-language'][0])){
            $available = available_lang(true);
      $lang = strtolower($headers['accept-language'][0]);
            if(!array_key_exists($lang, $available)){
                $lang = def_lang();
            }
    }
    else{
      $lang = def_lang();
    }
  }
  return $lang;
}

function available_lang($all=false){
  return LanguageInstance::available($all);
}

function def_lang(){
  $default = LanguageInstance::default();
  return $default['code'];
}

function get_lang($request, $lang=''){
  if(strlen($lang) == 0){
    $lang = def_lang();
  }

  $default = isset($request[$lang]) ? $request[$lang] : $request[def_lang()];
  return $default;
}

function elang($arr=[], $strict=false){
  $curr = current_lang();
  if(isset($arr[$curr])){
    return $arr[$curr];
  }
  if(!$strict && isset($arr[def_lang()])){
    return $arr[def_lang()];
  }
  return false;
}