<!DOCTYPE html>
<html lang="en">
<head>
	@include('partials.metadata')
</head>
<body>

@include('partials.header')
<div class="content-wrapper">
	@yield('content')
</div>
@include('partials.footer')

@include('partials.script')
</body>
</html>