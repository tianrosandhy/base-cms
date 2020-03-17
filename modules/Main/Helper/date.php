<?php
function redate($date_format, $date_data){
  $timezone = config('app.timezone');

  //generate date as GMT+0
  $date = new DateTime(date($date_format, $date_data), new DateTimeZone($timezone));
  $date->setTimezone(new DateTimeZone($timezone));
  return $date->format($date_format);
}

function datethis($date_object, $format='d M Y'){
  return redate($format, strtotime($date_object));
}

