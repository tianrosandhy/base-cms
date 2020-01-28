@extends ('main::master')

@section ('content')
<h3 class="display-4 mb-4">Dashboard</h3>

@if(config('cms.admin.google_analytic_dashboard'))
	@include ('main::module.google-analytic')
@endif

@stop
