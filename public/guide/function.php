<?php
function ctn($filename=''){
	$path = 'snippet/'.$filename.'.snippet';
	if(file_exists($path)){
		return htmlentities(file_get_contents($path));
	}
	return '';
}