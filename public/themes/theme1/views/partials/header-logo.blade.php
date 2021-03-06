<!-- Logo
============================================= -->
<?php
$logo = setting('site.logo', null);
$normal_logo = theme_asset('images/logo.png');
$large_logo = theme_asset('images/logo@2x.png');
if($logo){
	$thumb_list = thumbnail($logo);
	if(isset($thumb_list['small'])){
		$normal_logo = storage_url($thumb_list['small']);
	}
	if(isset($thumb_list['medium'])){
		$large_logo = storage_url($thumb_list['medium']);
	}
}
?>
<div id="logo">
	<a href="{{ url('/') }}" class="standard-logo"><img src="{{ $normal_logo }}" alt="Canvas Logo"></a>
	<a href="{{ url('/') }}" class="retina-logo"><img src="{{ $large_logo }}" alt="Canvas Logo"></a>
</div>