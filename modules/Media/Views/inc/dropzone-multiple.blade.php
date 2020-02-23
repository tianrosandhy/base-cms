<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}
?>
<input type="hidden" data-hash="{{ $hash }}" name="{{ $name }}" class="dropzone_uploaded listen_uploaded_image_multiple" value="{{ isset($value) ? $value : '' }}">
<div style="padding-bottom:.5em;">
	<?php
	$max_size = (file_upload_max_size(config('cms.max_filesize.image')) / 1024 /1024);
	?>
	<span style="opacity:.5; font-size:.7em; padding:0 .75em;">Maximum Upload Size : {{ number_format($max_size, 2) }} MB each</span>
</div>
<div class="dropzone custom-dropzone dz-clickable mydropzone-multiple" data-hash="{{ $hash }}" upload-limit="{{ intval($max_size) }}" data-target="{{ route('admin.media.upload') }}"></div>
