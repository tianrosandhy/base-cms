<?php
require('function.php');
if($_GET['page']){
	$path = 'pages/'.strip_tags($_GET['page']).'.php';
	if(is_file($path)){
		require($path);
		exit();
	}
}
header("HTTP/1.0 404 Not Found");