<!DOCTYPE html>
<html lang="en">
<head>
	@include('themes.themes2.views.partials.metadata')
</head>
<body>

@include('themes.themes2.views.partials.header')
<div class="content-wrapper">
	@yield('content')
</div>
@include('themes.themes2.views.partials.footer')

@include('themes.themes2.views.partials.script')
</body>
</html>