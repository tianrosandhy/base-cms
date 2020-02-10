@extends ('layouts.master')

@section ('slider')
	@include ('partials.hero-slider')
@endsection

@section ('content')
	@include ('pages.homepage-top-text')

	@include ('pages.homepage-services')
	@include ('pages.homepage-product')
	@include ('pages.homepage-blog')
	@include ('pages.contact-us')

@stop