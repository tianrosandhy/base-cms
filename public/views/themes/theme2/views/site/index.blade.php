@extends ('layouts.master')

@section ('slider')
	@include ('partials.hero-slider')
@endsection

@section ('content')
	@include ('pages.homepage-top-text')
    <div class="clear"></div>
    @include ('pages.homepage-parallax')
    <div class="clear"></div>
	@include ('pages.contact-us')
@stop