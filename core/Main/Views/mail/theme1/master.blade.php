<?php
$var = [
	'max-width' => 480,
	'logo' => url(env('APP_URL').'/styling/src/img/logo.png'),
];
?>
<!DOCTYPE html>
<html>
<head>
	@include ('main::mail.theme1.partials.metadata')
</head>
<body>
	<table width="{{ $var['max-width'] }}" style="margin: 32px auto; border: solid 1px #f5f5f5; box-shadow: 4px 4px 8px rgba(0,0,0,.05); color: #333333; border-radius: 8px">
		@include ('main::mail.theme1.partials.header')
		<tr>
			<td style="padding: 0 16px">
				<table>
					<tr>
						<td>
							<div class="content">
								@if(isset($title))
								<strong>{{ $title }}</strong>
								@endif

								@if(isset($content))
									{!! $content !!}
									{!! isset($additional_content) ? $additional_content : '' !!}
								@endif
							</div>

							@if(isset($button))
								@if(isset($button['url']) && isset($button['label']))
								<div>
									<a href="{{ url($button['url']) }}" style="color: #0089D1; font-weight: bold; text-decoration: none; border-bottom: dotted 1px #0089d1;">
										{{ $button['label'] }}
									</a>
								</div>
								@else
									@foreach($button as $btns)
										@if(isset($btns['url']) && isset($btns['label']))
										<div>
											<a href="{{ url($btns['url']) }}" style="color: #0089D1; font-weight: bold; text-decoration: none; border-bottom: dotted 1px #0089d1;">
												{{ $btns['label'] }}
											</a>
										</div>
										@endif
									@endforeach
								@endif
							@endif

							@include ('main::mail.theme1.partials.footer')
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table width="{{ $var['max-width'] }}" style="margin: auto;">
		<tr>
			<td>
				<p style="text-align: center; border-top: solid 1px #f5f5f5; color: #999999; margin-bottom: 32px; padding-top: 32px;">
					Copyright &copy; {{ setting('site.title') }} {{ date('Y') }}
				</p>
			</td>
		</tr>
	</table>
</body>
</html>

