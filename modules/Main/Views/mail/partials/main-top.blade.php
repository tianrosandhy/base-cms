<!-- MODULE ROW // -->
<tr>
	<td align="center" valign="top">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="color:#FFFFFF;" bgcolor="{{ $var['main-top-color'] }}">
			<tr>
				<td align="center" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="{{ $var['max-width'] }}" class="flexibleContainer">
						<tr>
							<td align="center" valign="top" width="{{ $var['max-width'] }}" class="flexibleContainerCell">
								<table border="0" cellpadding="30" cellspacing="0" width="100%">
									<tr>
										<td align="center" valign="top" class="textContent">
											@if(isset($title))
											<h1 style="color:#FFFFFF;line-height:100%;font-family:Helvetica,Arial,sans-serif;font-size:29px;font-weight:normal;margin-bottom:5px;text-align:center;">{{ isset($title) ? $title : '' }}</h1>
											@endif
											@if(isset($subheader))
											<h2 style="text-align:center;font-weight:normal;font-family:Helvetica,Arial,sans-serif;font-size:20px;margin-bottom:10px;color:#205478;line-height:135%;">{{ isset($subheader) ? $subheader : '' }}</h2>
											@endif
											@if(isset($top_description))
											<div style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:15px;margin-bottom:0;color:#FFFFFF;line-height:135%;">{{ isset($top_description) ? $top_description : '' }}</div>
											@endif
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