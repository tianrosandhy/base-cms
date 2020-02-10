<?php
$services = SiteInstance::service()->all();
$service_categories = SiteInstance::service()->categories();
?>
@if($services->count() > 0)
<div class="container">
	<section id="homepage-service" class="pb-5">
		<div class="heading-block topmargin-sm center">
			<h3>Our Projects</h3>
			<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore tempora, deserunt, vitae tempore fuga.</p>
		</div>


		<ul id="portfolio-filter" class="portfolio-filter clearfix" data-container="#portfolio">
			<li class="activeFilter"><a href="#" data-filter="*">Show All</a></li>
			@foreach($service_categories as $cat)
			<li><a href="#" class="prevent-default" data-filter=".pf-{{ slugify($cat->title) }}">{{ $cat->title }}</a></li>
			@endforeach
		</ul>

		<div id="portfolio-shuffle" class="portfolio-shuffle" data-container="#portfolio">
			<i class="icon-random"></i>
		</div>
		<div class="clear"></div>
		<div id="portfolio" class="portfolio grid-container portfolio-3 clearfix">
			@foreach($services as $row)
			<?php
			$rowcat = isset($row->category->title) ? slugify($row->category->title) : null;
			?>
			<article class="portfolio-item pf-media pf-{{ $rowcat }}">
				<div class="portfolio-image">
					<a href="{{ $row->getThumbnailUrl('image', 'large') }}" data-lightbox="image">
						<img src="{{ $row->getThumbnailUrl('image', 'medium') }}" alt="{{ $row->title }}">
					</a>
					<a href="{{ $row->getThumbnailUrl('image', 'large') }}" class="portfolio-overlay d-block" data-lightbox="image">
					</a>
				</div>
				<div class="portfolio-desc">
					<h3><a href="{{ $row->getThumbnailUrl('image', 'large') }}" data-lightbox="image">{{ $row->title }}</a></h3>
					@if(isset($row->category->title))
						<span><a href="#">{{ $row->category->title }}</a></span>
					@endif
				</div>
			</article>
			@endforeach
		</div>

	</section>	
</div>
@endif