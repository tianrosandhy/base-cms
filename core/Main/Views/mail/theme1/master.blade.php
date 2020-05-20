<!doctype html>
<html lang="en">
<head>
	@include ('main::mail.theme1.partials.metadata')
</head>
<body>
	<div id="wrapper" style="max-width:{{ $theme['max_width'] }}px;">
		<table style="width:100%; background:{{ $theme['wrapper_background'] }};" cellspacing="0" cellpadding=0>
			@include ('main::mail.theme1.partials.header')

			@if(isset($content))
			<tr>
				<td style="padding:1.5em; font-size:18px; line-height:1.45em; font-weight:300; color:{{ $theme['text_color_dark'] }}">
					{!! $content !!}
					{!! isset($additional_content) ? $additional_content : '' !!}
				</td>
			</tr>
			@endif
			@if(isset($button))
			<tr>
				<td style="padding:1.5em;" align="center">
					@if(isset($button['url']) && isset($button['label']))
					<a href="{{ $button['url'] }}" class="button" style="text-decoration:none; color:{{ $theme['text_color_light'] }}">{{ $button['label'] }}</a>
					@else
						@foreach($button as $btns)
							@if(isset($btns['url']) && isset($btns['label']))
							<a href="{{ $btns['url'] }}" class="button" style="text-decoration:none; color:{{ $theme['text_color_light'] }}">{{ $btns['label'] }}</a>
							@endif
						@endforeach
					@endif
				</td>
			</tr>
			@endif
		</table>
		@include ('main::mail.theme1.partials.footer')
	</div>
</body>
</html>