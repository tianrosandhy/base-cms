<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}
?>
<input type="hidden" data-hash="{{ $hash }}" name="{{ $name }}" class="dropzone_uploaded listen_uploaded_image" value="{{ isset($value) ? $value : '' }}">
<div class="row">
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div style="padding-bottom:.5em;">
			<?php
			$max_size = (file_upload_max_size(config('cms.max_filesize.image')) / 1024 /1024);
			?>
			<span style="opacity:.5; font-size:.7em; padding:0 .75em;">Maximum Upload Size : {{ number_format($max_size, 2) }} MB</span>
		</div>
        <div class="dropzone custom-dropzone dz-clickable mydropzone" data-hash="{{ $hash }}" upload-limit="{{ intval($max_size) }}" data-target="{{ admin_url('api/store-images') . ( isset($path) ? '?path='.$path : '' ) }}"></div>
	</div>
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div class="uploaded-holder" data-hash="{{ $hash }}">
			@if(isset($value))
				@if(strlen($value) > 0)
					<div class="uploaded">
						@if(ImageService::pathExists($value))
							<?php
							$image_set = allImageSet($value);
							?>
							<a href="{{ isset($image_set['origin-webp']) ? storage_url($image_set['origin-webp']) : (isset($image_set['origin']) ? storage_url($image_set['origin']) : '#') }}" data-fancybox="gallery">
								<picture>
									@if(isset($image_set['thumb-webp']))
									<source src="{{ storage_url($image_set['thumb-webp']) }}">
									@endif
									@if(isset($image_set['thumb']))
									<source src="{{ storage_url($image_set['thumb']) }}">
									@endif
									<img src="{{ storage_url($image_set['thumb']) }}" style="height:100px;">
								</picture>
							</a>
						@else
							<img src="{{ admin_asset('img/broken-image.jpg') }}" style="height:100px;">
						@endif
						<span class="remove-asset" data-hash="{{ $hash }}">&times;</span>
					</div>
				@endif
			@endif
		</div>		
	</div>
</div>
