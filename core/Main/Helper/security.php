<?php
function nonce_generate($age=86400){
  $time = time() + $age;
  return encrypt($time);
}

function nonce_validate($nonce=''){
  $test = decrypt($nonce);
  if($test){
    if($test > time()){
      return true;
    }
  }
  return false;
}

function clean_input($string=''){
  return preg_replace('/[^A-Za-z0-9\- ]/', '', $string);
}
