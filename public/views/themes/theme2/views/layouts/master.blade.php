<!DOCTYPE html>
<html  >
<head>
	@include ('partials.metadata')
</head>
<body>
@include ('partials.header')
@yield ('slider')

@yield ('content')

@include ('partials.footer')
@include ('partials.script')  
</body>
</html>