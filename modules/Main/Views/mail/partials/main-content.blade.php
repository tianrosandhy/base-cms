<!-- MODULE ROW // -->
<!--  The "mc:hideable" is a feature for MailChimp which allows
	you to disable certain row. It works perfectly for our row structure.
	http://kb.mailchimp.com/article/template-language-creating-editable-content-areas/
-->
<tr>
	<td align="center" valign="top">
		<!-- CENTERING TABLE // -->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" valign="top">
					<!-- FLEXIBLE CONTAINER // -->
					<table border="0" cellpadding="30" cellspacing="0" width="{{ $var['max-width'] }}" class="flexibleContainer">
						<tr>
							<td valign="top" width="{{ $var['max-width'] }}" class="flexibleContainerCell">

								<!-- CONTENT TABLE // -->
								<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td valign="top" class="flexibleContainerBox">

											<div style="text-align:{{ $var['content-align'] }};font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#5F5F5F;line-height:135%;">
												{!! isset($content) ? $content : '' !!}
											</div>

										</td>
									</tr>
								</table>
								<!-- // CONTENT TABLE -->

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
<!-- // MODULE ROW -->