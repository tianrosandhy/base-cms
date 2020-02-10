<?php
$banners = SiteInstance::banner()->all('id', 'ASC', true);
?>
@if($banners->count() > 0)
<section id="slider" class="slider-element slider-parallax">
	<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-pagi="true" data-loop="true" data-speed="450" data-autoplay="5000">
		@foreach($banners as $ban)
		<div>
			<img src="{{ $ban->getThumbnailUrl('image', 'extralarge') }}" alt="Slider">
			@if(setting('site.theme') == 'dark')
				<div class="dark-overlay"></div>
			@endif
			<div class="owl-caption">
				<div>
					<h2 class="caption-title">{{ $ban->title }}</h2>
					<p class="caption-description lead">{{ $ban->description }}</p>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</section>
@endif