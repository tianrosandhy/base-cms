<tr style="display:none; opacity:0; visibility:hidden;">
	<td>
		{!! isset($precontent) ? $precontent : '' !!}
	</td>
</tr>
<tr>
	<td style="padding:1.5em;" align="center">
		<a href="{{ url('/') }}">
			@include ('main::template.components.logo', ['height' => 60, 'width' => 150])
		</a>
	</td>
</tr>
@if(isset($title) || isset($subtitle))
<tr>
	<td style="padding:1.5em 2.5em;" bgcolor="{{ $theme['primary_color'] }}">
		<h1 style="color:{{ $theme['text_color_light']  }}">{{ $title }}</h1>
		@if(isset($subtitle))
		<h2 style="color:{{ $theme['text_color_light'] }}">{{ $subtitle }}</h2>
		@endif
	</td>
</tr>
@endif
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
