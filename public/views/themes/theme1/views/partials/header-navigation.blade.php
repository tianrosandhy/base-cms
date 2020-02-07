@if(SiteInstance::navigation()->structure('Header'))
<ul>
	@foreach(SiteInstance::navigation()->structure('Header') as $label => $data)
		@include('include.navigation-item', [
			'level' => 0
		])
	@endforeach
</ul>
@endif
