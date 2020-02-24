@extends ('main::master')

@include ('media::use-media')

@section ('content')
<h2 class="mb-3">{{ $title }}</h2>

{!! MediaInstance::input('image[]', '{"id" : 2, "thumb" : "origin"}') !!}

@stop