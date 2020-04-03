<?php
$mainTitle = setting('site.title');
$mainDescription = setting('site.subtitle');

if(isset($title)){
  echo $title . ' - '. $mainTitle;
}
else{
  echo $mainTitle . ' - '. $mainDescription;
}
?>