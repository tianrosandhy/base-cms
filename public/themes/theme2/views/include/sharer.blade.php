<div class="si-share noborder clearfix py-3">
	<span>Share this Post:</span>
	<div>
		<a target="_blank" href="https://www.facebook.com/sharer.php?u={{ $url }}" class="social-icon si-borderless si-facebook">
			<i class="fa fa-facebook"></i>
		</a>
		<a target="_blank" href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $text }}" class="social-icon si-borderless si-twitter">
			<i class="fa fa-twitter"></i>
		</a>
		<a target="_blank" href="https://api.whatsapp.com/send?text={!! urlencode($title.' - '.$url) !!}" class="social-icon si-borderless si-whatsapp">
			<i class="fa fa-whatsapp"></i>
		</a>
	</div>
</div>
