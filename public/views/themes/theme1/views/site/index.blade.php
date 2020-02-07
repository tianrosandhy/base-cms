@extends ('layouts.master')

@section ('slider')
	@include ('partials.hero-slider')
@endsection

@section ('content')
@include ('pages.homepage-top-text')
<div class="container">
	@include ('pages.homepage-services')

	@include ('pages.homepage-blog')
</div>
@stop