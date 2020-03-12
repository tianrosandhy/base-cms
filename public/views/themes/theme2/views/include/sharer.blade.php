<div class="si-share noborder clearfix">
	<span>Share this Post:</span>
	<div>
		<a target="_blank" href="https://www.facebook.com/sharer.php?u={{ $url }}" class="social-icon si-borderless si-facebook">
			<i class="icon-facebook"></i>
		</a>
		<a target="_blank" href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $text }}" class="social-icon si-borderless si-twitter">
			<i class="icon-twitter"></i>
		</a>
		<a target="_blank" href="https://api.whatsapp.com/send?text={!! urlencode($title.' - '.$url) !!}" class="social-icon si-borderless si-whatsapp">
			<i class="icon-whatsapp"></i>
		</a>
	</div>
</div>
