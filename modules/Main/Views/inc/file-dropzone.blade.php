<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}
?>
<input type="hidden" data-hash="{{ $hash }}" name="{{ $name }}" class="dropzone_uploaded listen_uploaded_file" value="{{ isset($value) ? $value : '' }}">
<div class="row">
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div>
			<span style="font-size:.7em; padding:0 1em; opacity:.5;">Maximum Upload Size : {{ number_format((file_upload_max_size(config('cms.max_filesize.file')) / 1024 /1024), 2) }} MB</span>
		</div>
		<div class="dropzone custom-dropzone dz-clickable filedropzone" data-hash="{{ $hash }}" data-target="{{ admin_url('api/store-files') }}"></div>
	</div>
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div class="uploaded-holder" data-hash="{{ $hash }}">
			@if(isset($value))
				<?php
				$parse = json_decode($value, true);
				?>
				@if($parse)
					@if(ImageService::urlExists($parse['path']))
					<div class="uploaded">
						<span class="file-alias">{{ $parse['filename'] }}</span>
						<span class="remove-asset-file" data-hash="{{ $hash }}">&times;</span>
					</div>
					@endif
				@endif
			@endif
		</div>		
	</div>
</div>
