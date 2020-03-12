@extends ('layouts.master')

@section ('slider')
	@include ('partials.hero-slider')
@endsection

@section ('content')
	@include ('pages.homepage-top-text')
	@include ('pages.homepage-clients')
	@include ('pages.homepage-parallax')
	@include ('pages.homepage-blog')
@stop