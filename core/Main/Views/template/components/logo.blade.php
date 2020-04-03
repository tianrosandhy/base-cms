<?php
$logo_image = admin_asset(config('cms.admin.logo'));
if(strlen(setting('admin.logo')) > 0){
	if(Storage::exists(setting('admin.logo'))){
		$logo_image = storage_url(setting('admin.logo'));
	}
}
?>
<img src="{{ $logo_image }}" alt="{{ setting('site.title') }}" style="object-fit:contain; {!! isset($width) ? ($width > 0 ? 'width:'.$width.'px;' : '') : '' !!} {!! isset($height) ? ($height > 0 ? 'height:'.$height.'px;' : '') : '' !!}">
