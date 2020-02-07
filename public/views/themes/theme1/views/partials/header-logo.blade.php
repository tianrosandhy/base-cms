<!-- Logo
============================================= -->
<?php
$logo = setting('site.logo', null);
$normal_logo = asset('styling/images/logo.png');
$large_logo = asset('styling/images/logo@2x.png');
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
	<a href="#" class="standard-logo"><img src="{{ $normal_logo }}" alt="Canvas Logo"></a>
	<a href="#" class="retina-logo"><img src="{{ $large_logo }}" alt="Canvas Logo"></a>
</div>