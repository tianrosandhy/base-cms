<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}
?>
<input type="hidden" data-hash="{{ $hash }}" name="{{ $name }}" class="dropzone_uploaded listen_uploaded_image_multiple" value="{{ isset($value) ? $value : '' }}">
<div class="row">
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div class="dropzone custom-dropzone dz-clickable mydropzone-multiple" data-hash="{{ $hash }}" data-target="{{ admin_url('api/store-images') . ( isset($path) ? '?path='.$path : '' ) }}"></div>
	</div>
	<div class="{{ isset($horizontal) ? 'col-sm-6' : 'col-sm-12' }}">
		<div class="uploaded-holder" data-hash="{{ $hash }}">
			@if(isset($value))
				<?php
				$trm = explode("|", $value);
				?>
				@foreach($trm as $val)
					@if(strlen($val) > 0)
						<div class="uploaded">
							@if(ImageService::pathExists($val))
							<img src="{{ storage_url($val) }}" style="height:60px;">
							@else
							<img src="{{ admin_asset('img/broken-image.jpg') }}" alt="Broken Image" style="height:100px;">
							@endif
							<span class="remove-asset-multiple" data-hash="{{ $hash }}" data-value="{{ $val }}">&times;</span>
						</div>
					@endif
				@endforeach
			@endif
		</div>		
	</div>
</div>
