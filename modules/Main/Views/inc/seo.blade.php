<?php
if(!isset($mainTitle)){
	$mainTitle = setting('site.title');
}
if(!isset($mainDescription)){
	$mainDescription = setting('site.subtitle');
}


if(strpos($config['image'], 'http') === false){
	//image as path
	$image_url = storage_url($config['image']);
	if(Storage::exists($config['image'])){
		try{
			list($image_width, $image_height) = getimagesize(Storage::path($config['image']));
		}catch(\Exception $e){
			//do nothing -_-
		}
	}
}
else{
	$image_url = $config['image'];
}

$og_title = strlen($config['title']) > 0 ? $config['title'].' - '.$mainTitle : $mainTitle ;
?>
<meta name="description" content="{{ $config['description'] }}">
<meta name="keywords" content="{{ $config['keywords'] }}">
<meta property="fb:app_id" content="{{ $config['fb_app'] }}">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:type" content="{{ $config['type'] }}">
<meta property="og:title" content="{{ $og_title }}">
<meta property="og:image" itemprop="image" content="{{ $image_url }}">
@if(isset($image_width))
<meta property="og:image:width" content="{{ $image_width }}">
@endif
@if(isset($image_height))
<meta property="og:image:height" content="{{ $image_height }}">
@endif
<meta property="og:description" content="{{ $config['description'] }}">
<meta property="og:site_name" content="{{ $mainTitle }}">
<meta property="og:locale" content="en_US">
<meta property="article:author" content="{{ $config['author'] }}">
<meta name="twitter:card" content="summary">
@if(strlen($config['twitter_username']) > 0)
<meta name="twitter:site" content="@{{ $twitter_username }}">
<meta name="twitter:creator" content="@{{ $twitter_username }}">
@endif
<meta name="twitter:url" content="{{ request()->fullUrl() }}">
<meta name="twitter:title" content="{{ $og_title }}">
<meta name="twitter:description" content="{{ strlen($config['description']) > 200 ? substr($config['description'], 0, 197).'...' : $config['description'] }}">
<meta name="twitter:image" content="{{ $image_url }}">