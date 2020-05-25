<tr style="display:none; opacity:0; visibility:hidden;">
	<td>
		{!! isset($precontent) ? $precontent : '' !!}
	</td>
</tr>
<tr>
	<td style="padding: 16px;">
		@include ('main::template.components.logo', ['height' => 60, 'width' => 150])
	</td>
</tr>
@if(isset($banner_image))
<tr>
	<td>
	@if(is_array($banner_image))
		@foreach($banner_image as $bimg)
		<img src="{{ $bimg }}" style="width:100%; margin:0; display:block;" alt="Banner Image">
		@endforeach
	@else
		<img src="{{ $banner_image }}" style="width:100%; margin:0; display:block;" alt="Banner Image">
	@endif
	</td>
</tr>
@endif
