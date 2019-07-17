<?php
if(!isset($hash)){
	$hash = sha1(md5(time() . rand(1, 10000000) . uniqid() ));
}
if(!isset($name)){
	$name = 'image';
}

if(!isset($path)){
	$path = null;
}

if(!isset($horizontal)){
	$horizontal = false;
}
?>
<div style="padding:0 .5em" data-hash="{{ $name }}-{{ $hash }}">

	@if($horizontal)
	<div class="row">
		<div class="col-sm-6">
	@endif

	<div class="uploaded-holder" data-hash="{{ $name }}-{{ $hash }}">
		@if(isset($value))
			@if(strlen($value) > 0)
				<div class="uploaded">
					@if(ImageService::pathExists($value))
						<img src="{{ storage_url($value) }}">
					@else
						<img src="{{ admin_asset('img/broken-image.jpg') }}">
					@endif
					<span class="remove-asset" data-hash="{{ $name }}-{{ $hash }}">&times;</span>
				</div>
			@else
			<div class="uploaded">
				<img src="{{ admin_asset('img/broken-image.jpg') }}">
				<span class="remove-asset" data-hash="{{ $name }}-{{ $hash }}">&times;</span>
			</div>
			@endif
		@else
		<div class="uploaded">
			<img src="{{ admin_asset('img/broken-image.jpg') }}">
			<span class="remove-asset" data-hash="{{ $name }}-{{ $hash }}">&times;</span>
		</div>
		@endif
	</div>		

	@if($horizontal)
		</div>
		<div class="col-sm-6">
	@endif

	<input type="hidden" data-hash="{{ $name }}-{{ $hash }}" name="{{ $name }}" class="listen_uploaded_image" value="{{ isset($value) ? $value : '' }}">
	<span class="label label-primary">Ratio : {{ $x_ratio }} : {{ $y_ratio }}</span>	
	<div class="padd">
		<label class="cropper-label-button">
			<input type="file" accept="image/*" id="{{ $name }}-{{ $hash }}" class="cropper-file">
			<span class="upload-cropper btn btn-primary"><i class="fa fa-upload"></i> Upload Image</span>
		</label>
	</div>

	<div class="cropper-exec" data-id="{{ $name }}-{{ $hash }}" style="display:none;">
		<img src="" class="cropper-image" data-id="{{ $name }}-{{ $hash }}" data-x-ratio="{{ $x_ratio }}" data-y-ratio="{{ $y_ratio }}">
		<div align="center">
			<a href="#" class="btn btn-sm btn-danger btn-cancel-crop">Cancel</a>
			<a href="#" class="btn btn-sm btn-info cropper-runner" data-target="{{ $name }}-{{ $hash }}" data-upload="{{ route('api.cropper', ['path' => $path]) }}"><i class="fa fa-crop"></i> Crop</a>
		</div>
	</div>


	@if($horizontal)
		</div>
	</div>
	@endif

</div>
