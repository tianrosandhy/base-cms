@if(SiteInstance::navigation()->structure('Default'))
<ul class="navbar-nav nav-dropdown">
	@foreach(SiteInstance::navigation()->structure('Default') as $label => $data)
		@include('include.navigation-item', [
			'level' => 0
		])
	@endforeach
	@if(LanguageInstance::isActive())
	<?php
	$default_language = LanguageInstance::active();
	?>
	<li class="nav-item dropdown">
		<a href="#" class="nav-link link text-white display-4 dropdown-toggle" data-toggle="dropdown-submenu">
			<img src="{{ $default_language['image'] }}" alt="{{ $default_language['title'] }}" style="height:40px;">
		</a>
		<div class="dropdown-menu">
	  	@foreach(LanguageInstance::available(true) as $lang)
	    <a class="dropdown-item" href="?lang={{ $lang['code'] }}">
	    	<img src="{{ $lang['image'] }}" alt="{{ $lang['title'] }}" style="height:40px;">
	    </a>
	    @endforeach
		</div>
	</li>
	@endif
</ul>
@endif
