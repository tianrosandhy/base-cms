<section id="page-title">
	<div class="container clearfix">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
			@if(isset($path))
				@foreach($path as $label => $url)
				<li class="breadcrumb-item"><a href="{{ url($url) }}">{{ $label }}</a></li>
				@endforeach
			@endif
			@if(isset($title))
			<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
			@endif
		</ol>
	</div>
</section>