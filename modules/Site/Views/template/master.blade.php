<!DOCTYPE html>
<html lang="en">
<head>
	@include ('site::template.partials.metadata')
</head>
<body>

@include ('site::template.partials.header')
<div class="content-wrapper">
	@yield('content')
</div>
@include ('site::template.partials.footer')

@include ('site::template.partials.script')
</body>
</html>