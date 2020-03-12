<?php
$logo = setting('site.logo', null);
$normal_logo = admin_asset(config('cms.front.logo'));
if($logo){
	$thumb_list = thumbnail($logo);
	if(isset($thumb_list['small'])){
		$normal_logo = storage_url($thumb_list['small']);
	}
}
?>
<img src="{{ $normal_logo }}" alt="{{ setting('site.title') }}" style="height: 3.8rem;">
