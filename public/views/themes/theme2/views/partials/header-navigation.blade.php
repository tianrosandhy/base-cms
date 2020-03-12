@if(SiteInstance::navigation()->structure('Default'))
<ul class="navbar-nav nav-dropdown" data-app-modern-menu="true">
	@foreach(SiteInstance::navigation()->structure('Default') as $label => $data)
		@include('include.navigation-item', [
			'level' => 0
		])
	@endforeach
</ul>
@endif
