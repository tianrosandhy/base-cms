<table bgcolor="{{ $var['background-color'] }}" border="0" cellpadding="0" cellspacing="0" width="{{ $var['max-width'] }}" id="emailFooter">
	<tr>
		<td align="center" valign="top">
			<!-- CENTERING TABLE // -->
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="center" valign="top">
						<!-- FLEXIBLE CONTAINER // -->
						<table border="0" cellpadding="0" cellspacing="0" width="{{ $var['max-width'] }}" class="flexibleContainer">
							<tr>
								<td align="center" valign="top" width="{{ $var['max-width'] }}" class="flexibleContainerCell">
									<table border="0" cellpadding="30" cellspacing="0" width="100%">
										<tr>
											<td valign="top" bgcolor="{{ $var['background-color'] }}">
											@if(isset($unsubscribe_url) || isset($additional_footer))

												@if(isset($unsubscribe_url))
												<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
													<div>If you do not want to receive emails from us, you can <a href="{{ $unsubscribe_url }}" target="_blank" style="text-decoration:none;color:#828282;"><span style="color:#828282;">unsubscribe</span></a>.</div>
												</div>
												@endif
												@if(isset($additional_footer))
												<div style="font-family:Helvetica,Arial,sans-serif;font-size:13px;color:#828282;text-align:center;line-height:120%;">
													<div>{!! $additional_footer !!}</div>
												</div>
												@endif
											@endif
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!-- // FLEXIBLE CONTAINER -->
					</td>
				</tr>
			</table>
			<!-- // CENTERING TABLE -->
		</td>
	</tr>
</table>