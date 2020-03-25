<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
	@include ('partials.metadata')
</head>

<body class="stretched">

	<div id="wrapper" class="clearfix">
		@include ('partials.header')
		@yield ('slider')
		<div class="clear"></div>
		<section id="content">
			<div class="content-wrap nopadding">
				@yield ('content')
			</div>
		</section>
		@include ('partials.footer')
	</div>

	<div id="gotoTop" class="icon-angle-up"></div>

	@include ('partials.script')
</body>
</html>
