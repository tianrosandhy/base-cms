<?php
$products = SiteInstance::product()->paginate(10);
?>
@if($products->count() > 0)
<section id="homepage-product" class="pt-5 pb-5 bg-dark">
	<div class="container">
		<div class="heading-block topmargin-sm center text-white">
			<h3 class="text-white">Our Products</h3>
			<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias dolore tempora, deserunt, vitae tempore fuga.</p>
		</div>

		<div id="oc-images" class="owl-carousel image-carousel carousel-widget" data-margin="20" data-nav="true" data-pagi="true" data-items-xs="2" data-items-sm="3" data-items-lg="4" data-items-xl="5">
			@foreach($products as $row)
			<div class="oc-item">
				<a href="#">
					{!! $row->srcSet('image', 'cropped', [
						'alt' => $row->title,
						'title' => $row->title
					]) !!}
				</a>
			</div>
			@endforeach
		</div>
	</div>
</section>
@endif