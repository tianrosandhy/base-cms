<header id="header" class="{{ setting('site.theme') == 'dark' ? 'transparent-header dark' : 'full-header' }}">
	<div id="header-wrap">
		<div class="container clearfix">
			<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
			@include ('partials.header-logo')
			<nav id="primary-menu">
				@include ('partials.header-navigation')
				@include ('partials.header-search')
			</nav><!-- #primary-menu end -->
		</div>
	</div>
</header><!-- #header end -->