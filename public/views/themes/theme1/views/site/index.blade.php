@extends ('layouts.master')

@section ('slider')
	@include ('partials.hero-slider')
@endsection

@section ('content')
<div class="container">

	@include ('pages.homepage-blog')

</div>
@stop