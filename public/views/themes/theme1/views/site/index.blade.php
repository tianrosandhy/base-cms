<?php
$posts = SiteInstance::post()->paginate(10);
?>
@extends ('layouts.master')

@section ('slider')
<section id="slider" class="slider-element slider-parallax">
	<div id="oc-slider" class="owl-carousel carousel-widget" data-margin="0" data-items="1" data-pagi="true" data-loop="true" data-speed="450" data-autoplay="5000">
		<a href="#">
			<img src="{{ asset('styling/images/slider/full/1.jpg') }}" alt="Slider">
			<div class="owl-caption">
				<div>
					<h2 class="caption-title">Lorem ipsum dolor sit amet.</h2>
					<p class="caption-description lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias minus nostrum modi repudiandae dolorum repellat, temporibus, incidunt et fugit illo!</p>
				</div>
			</div>
		</a>
		<a href="#">
			<img src="{{ asset('styling/images/slider/full/2.jpg') }}" alt="Slider">
			<div class="owl-caption">
				<div>
					<h2 class="caption-title">Lorem ipsum dolor sit amet.</h2>
					<p class="caption-description lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias minus nostrum modi repudiandae dolorum repellat, temporibus, incidunt et fugit illo!</p>
				</div>
			</div>
		</a>
		<a href="#">
			<img src="{{ asset('styling/images/slider/full/3.jpg') }}" alt="Slider">
			<div class="owl-caption">
				<div>
					<h2 class="caption-title">Lorem ipsum dolor sit amet.</h2>
					<p class="caption-description lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias minus nostrum modi repudiandae dolorum repellat, temporibus, incidunt et fugit illo!</p>
				</div>
			</div>
		</a>
	</div>
</section>
@endsection

@section ('content')
<div class="container">
	<h2 class="mt-3 mb-3 display-4">This is just <b>Theme 1</b> Scaffolding Example</h2>

	@if($posts->count() == 0)
	<div class="alert alert-warning">Sorry, we dont have any post to show right now.</div>
	@endif
	@foreach($posts as $row)
	<?php
	$data = SiteInstance::post()->setData($row)->structure();
	?>
	<div class="card card-body mt-3 mb-3">
		<div class="row">
			<div class="col-sm-3">
				<img src="{{ $data['image'] }}" style="width:100%;" alt="{{ $data['title'] }}">				
			</div>
			<div class="col-sm-9">
				<h4>{{ $data['title'] }}</h4>
				<div class="ket">
					@foreach($data['tags'] as $tg)
					<span class="badge badge-primary">{{ $tg }}</span>
					@endforeach
				</div>
				<div class="ket">
					Category : 
					@foreach($data['category'] as $cat)
					<span class="badge badge-secondary">{{ $cat['name'] }}</span>
					@endforeach
				</div>

				<div class="excerpt mt-3">
					<p class="lead">
						@if(isset($data['excerpt']))
						{!! $data['excerpt'] !!}
						@endif
					</p>
				</div>
			</div>
		</div>
	</div>
	@endforeach

	{{ $posts->links() }}
</div>
@stop