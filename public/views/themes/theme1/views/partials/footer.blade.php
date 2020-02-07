<!-- Footer
============================================= -->
<footer id="footer" class="dark">
	<!-- Copyrights
	============================================= -->
	<div id="copyrights">
		<div class="container clearfix">
			<div class="col_half">
				Copyrights &copy; {{ date('Y') }} All Rights Reserved.<br>
				<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a></div>
			</div>

			<div class="col_half col_last tright">
				<div class="fright clearfix">
					<?php
					$social_list = ['facebook', 'twitter', 'instagram', 'youtube', 'whatsapp'];
					?>
					@foreach($social_list as $soc)
					@if(setting('social.'.$soc))
					<a href="{{ setting('social.'.$soc) }}" class="social-icon si-small si-borderless si-{{ $soc }}">
						<i class="icon-{{ $soc }}"></i>
						<i class="icon-{{ $soc }}"></i>
					</a>
					@endif
					@endforeach
				</div>

				<div class="clear"></div>

				<i class="icon-envelope2"></i> {{ setting('site.email', 'your@email.com') }} <span class="middot">&middot;</span> <i class="icon-headphones"></i> {{ setting('site.phone', '08123456789') }}
			</div>

		</div>

	</div><!-- #copyrights end -->

</footer><!-- #footer end -->