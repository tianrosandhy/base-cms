@if(SiteInstance::navigation()->structure('Default'))
<ul>
	@foreach(SiteInstance::navigation()->structure('Default') as $label => $data)
		@include('include.navigation-item', [
			'level' => 0
		])
	@endforeach
</ul>
@endif
