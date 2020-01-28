<!DOCTYPE html>
<html lang="en">
<head>
	@include('themes.themes1.views.partials.metadata')
</head>
<body>

@include('themes.themes1.views.partials.header')
<div class="content-wrapper">
	@yield('content')
</div>
@include('themes.themes1.views.partials.footer')

@include('themes.themes1.views.partials.script')
</body>
</html>