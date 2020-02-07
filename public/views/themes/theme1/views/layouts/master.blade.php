<!DOCTYPE html>
<html dir="ltr" lang="id-ID">
<head>
	@include ('partials.metadata')
</head>

<body class="stretched sticky-responsive-menu">
	<div id="wrapper" class="clearfix">
		@include ('partials.header')
		@yield ('slider')
		<section id="content">
			<div class="content-wrap">
				@yield ('content')
			</div>
		</section>
		@include ('partials.footer')
	</div>
	<div id="gotoTop" class="icon-angle-up"></div>
	@include ('partials.script')
</body>
</html>