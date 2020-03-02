<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="{{ setting('site.author', 'TianRosandhy') }}" />
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700|Raleway:300,400,700|Crete+Round:400i" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/bootstrap.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('admin_theme/vendor/font-awesome/css/font-awesome.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/style.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/dark.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/font-icons.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/animate.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/magnific-popup.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/responsive.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('styling/css/additional.css') }}" type="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>{{ isset($title) ? $title .' | '. setting('site.title') : setting('site.title') }}</title>
@stack ('style')