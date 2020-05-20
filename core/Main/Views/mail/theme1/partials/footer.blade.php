<table class="footer" style="width:100%; font-size:12px; margin:1.5em auto; opacity:.75">
	<tr>
		<td align="center" style="color:{{ $theme['text_color_dark'] }}">
			@if(isset($footer_text))
			<div>{!! $footer_text !!}</div>
			@endif
		</td>
	</tr>
	<tr>
		<td align="center" style="color:{{ $theme['text_color_dark'] }}">
			Copyright &copy; {{ date('Y') }} {{ setting('site.title') }}
		</td>
	</tr>
</table>
