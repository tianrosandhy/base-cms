@extends ('main::master')

@section ('content')
<div class="header-box">
	<h3 class="display-4 mb-4">Dashboard</h3>
</div>

<div class="content-box">
	@if(config('cms.admin.google_analytic_dashboard'))
		@include ('main::module.google-analytic')
	@endif
</div>

@stop
