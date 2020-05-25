<div style="margin: 32px; height: 1px; background: #f5f5f5"></div>
<p style="margin-bottom: 16px;">
	@if(isset($footer_text))
		{!! $footer_text !!}
	@else
	Hubungi kami untuk pertanyaan lebih lanjut
	@endif
</p>
<p style="font-weight: bold; margin-bottom: 32px;">
	<a href="mailto:{{ setting('site.email', 'admin@localhost') }}" style="margin: 0 16px; color: #0089D1; text-decoration: none; border-bottom: dotted 1px #0089d1;">
		{{ setting('site.email', 'admin@localhost') }}
	</a>
	<br>
	<a href="tel:{{ setting('site.phone', '089622224614') }}" style="margin: 0 16px; color: #0089D1; text-decoration: none; border-bottom: dotted 1px #0089d1; display:inline-block;">
		{{ setting('site.phone', '089622224614') }}
	</a>
</p>