<?php
$var = [
	'max-width' => 500,
	'background-color' => '#f0f0f0',
	'main-top-color' => '#0089d1',
	'button-color' => '#37b349',
	'button-text-color' => '#fff',
	'logo' => asset('styling/images/logo.png'),
	'logo-height' => 50,
	'content-align' => 'center',
];
?>
<!--
** Profile **
Licensed under MIT (https://github.com/charlesmudy/responsive-html-email-template/blob/master/LICENSE)
Designed by Shina Charles Memud
Respmail v1.2 (http://charlesmudy.com/respmail/)
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		@include ('main::mail.partials.metadata')
	</head>
	<body bgcolor="{{ $var['background-color'] }}" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

		<center style="background-color:{{ $var['background-color'] }};">
			<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="table-layout: fixed;max-width:100% !important;width: 100% !important;min-width: 100% !important;">
				<tr>
					<td align="center" valign="top" id="bodyCell">
						@include ('main::mail.partials.header')

						<!-- EMAIL BODY // -->
						<table bgcolor="#FFFFFF"  border="0" cellpadding="0" cellspacing="0" width="{{ $var['max-width'] }}" id="emailBody" style="border-radius:10px; overflow:hidden; box-shadow:1px 2px 7px #999;">
							@include ('main::mail.partials.main-top')
							@include ('main::mail.partials.main-content')

							@if(isset($additional_view))
							{!! $additional_view !!}
							@endif

							@include ('main::mail.partials.main-action-button')

						</table>
						<!-- // END -->
						@include ('main::mail.partials.unsubscribe')
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>