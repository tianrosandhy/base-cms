<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
<meta name="author" content="{{ setting('site.author', 'TianRosandhy') }}" />
<title>{{ isset($title) ? $title .' | '. setting('site.title') : setting('site.title') }}</title>
{!! isset($seo) ? $seo : null !!}
<link rel="stylesheet" href="{{ theme_asset('web/assets/mobirise-icons/mobirise-icons.css') }}">
<link rel="stylesheet" href="{{ theme_asset('bootstrap/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('bootstrap/css/bootstrap-grid.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('bootstrap/css/bootstrap-reboot.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('socicon/css/styles.css') }}">
<link rel="stylesheet" href="{{ theme_asset('dropdown/css/style.css') }}">
<link rel="stylesheet" href="{{ theme_asset('tether/tether.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin_theme/vendor/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ theme_asset('theme/css/style.css') }}">
<link rel="preload" as="style" href="{{ theme_asset('mobirise/css/mbr-additional.css') }}">
<link rel="stylesheet" href="{{ theme_asset('mobirise/css/mbr-additional.css') }}" type="text/css">
@if(setting('site.favicon'))
<link rel="icon" href="{{ Storage::url(setting('site.favicon')) }}">
@endif
@stack ('style')