<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="{{ url('/') }}">{{ setting('site.title') }}</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarNav">
		@if(SiteInstance::navigation()->structure('Header'))
		<ul class="navbar-nav">
			@foreach(SiteInstance::navigation()->structure('Header') as $label => $data)
				@include('themes.themes1.views.include.navigation-item', [
					'level' => 0
				])
			@endforeach
		</ul>
		@endif
	</div>

	<div class="pull-right">
		<a href="{{ admin_url('/') }}"><i class="fa fa-key"></i> Go to Admin Page</a>
	</div>
</nav>