<div class="section header-stick">
	<div class="container clearfix">
		<div class="heading-block bottommargin-sm">
			<h3>Automation system should make your life easier, not more complex</h3>
		</div>

		<?php
		$service_category = SiteInstance::service()->categories(true);
		?>
		@if($service_category->count() > 0)
		<div class="row justify-content-center">
			<?php
			$col_width = 12 / $service_category->count();
			if($col_width < 3){
				$col_width = 24 / $service_category->count();
			}
			$col_width = floor($col_width);
			?>
			@foreach($service_category as $row)
			<div class="col-sm-{{ $col_width }}">
				<div class="feature-box fbox-plain">
					<div class="fbox-icon bounceIn animated" data-animate="bounceIn">
						<img src="{{ $row->getThumbnailUrl('image', 'small') }}" alt="{{ $row->title }}">
					</div>
					<h3>{{ $row->title }}</h3>
					<p>{{ $row->excerpt }}</p>
				</div>
			</div>
			@endforeach
		</div>
		@endif

		<div>
			<a href="#" class="button button-3d button-dark button-large center" style="margin-top: 30px;">Check our Products</a>
		</div>

	</div>
</div>