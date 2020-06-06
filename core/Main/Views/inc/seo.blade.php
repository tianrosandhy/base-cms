<?php
if(!isset($mainTitle)){
	$mainTitle = setting('site.title');
}
if(!isset($mainDescription)){
	$mainDescription = setting('site.subtitle');
}


if(strpos($config['image'], 'http') === false){
	//if image is in json file, convert first
	$json_img = json_decode($config['image'], true);
	if(isset($json_img['id'])){
		$image_url = MediaInstance::readJsonPath($config['image']);
		if(strpos($image_url, 'http') === false){
			$image_url = Storage::url($image_url);
		}
	}
	else{
		if(strlen($config['image']) > 0){
			$image_url = Storage::url($config['image']);
		}
		else{
			$image_url = null;
		}
	}

	//image as path
	if(Storage::exists($image_url)){	
		try{
			list($image_width, $image_height) = getimagesize(Storage::path($image_url));
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
@if(isset($config['description']))
<meta name="description" content="{{ $config['description'] }}">
@endif
@if(isset($config['keyword']))
<meta name="keywords" content="{{ $config['keyword'] }}">
@endif
@if(setting('seo.fb_app'))
<meta property="fb:app_id" content="{{ setting('seo.fb_app') }}">
@endif
@if(isset($config['url']))
<meta property="og:url" content="{{ url($config['url']) }}">
@endif
<meta property="og:title" content="{{ $og_title }}">
@if(isset($image_url))
<meta property="og:image" itemprop="image" content="{{ $image_url }}">
@endif
@if(isset($image_width))
<meta property="og:image:width" content="{{ $image_width }}">
@endif
@if(isset($image_height))
<meta property="og:image:height" content="{{ $image_height }}">
@endif
@if(isset($config['description']))
<meta property="og:description" content="{{ $config['description'] }}">
@endif
<meta property="og:site_name" content="{{ $mainTitle }}">
<meta property="og:locale" content="{{ current_lang() }}">
<?php
$author = isset($config['author']) ? $config['author'] : (setting('seo.author', null));
?>
@if($author)
<meta property="article:author" content="{{ $author }}">
@endif
<meta name="twitter:card" content="summary">
@if(setting('seo.twitter_username'))
<meta name="twitter:site" content="@{{ setting('seo.twitter_username') }}">
<meta name="twitter:creator" content="@{{ setting('seo.twitter_username') }}">
@endif
@if(isset($config['url']))
<meta name="twitter:url" content="{{ url($config['url']) }}">
@endif
<meta name="twitter:title" content="{{ $og_title }}">
@if(isset($config['description']))
<meta name="twitter:description" content="{{ strlen($config['description']) > 200 ? substr($config['description'], 0, 197).'...' : $config['description'] }}">
@endif
@if(isset($image_url))
<meta name="twitter:image" content="{{ $image_url }}">
@endif