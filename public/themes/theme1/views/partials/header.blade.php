<header id="header" class="{{ themeoption('styling.header.transparent_first') ? 'transparent-header' : '' }}">
	<div id="header-wrap">
		<div class="container clearfix">
			<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
			@include ('partials.header-logo')
			<nav id="primary-menu">
				@include ('partials.header-navigation')

				@if(LanguageInstance::isActive())
				<?php
				$default_language = LanguageInstance::active();
				?>
				<div class="dropdown lang-switcher">
				  <button class="btn dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <img src="{{ $default_language['image'] }}" alt="{{ $default_language['title'] }}">
				  </button>
				  <div class="dropdown-menu" aria-labelledby="languageDropdown">
				  	@foreach(LanguageInstance::available(true) as $lang)
				    <a class="dropdown-item" href="?lang={{ $lang['code'] }}">
				    	<img src="{{ $lang['image'] }}" alt="{{ $lang['title'] }}">
				    </a>
				    @endforeach
				  </div>
				</div>
				@endif

				@include ('partials.header-search')
			</nav><!-- #primary-menu end -->
		</div>
	</div>
</header><!-- #header end -->