<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>
@include ('main::template.components.title')
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta content="tianrosandhy" name="author" />
<!-- plugins:css -->
<link rel="stylesheet" href="{{ admin_asset('vendor/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ admin_asset('vendor/mdi/css/materialdesignicons.min.css') }}">
<link rel="stylesheet" href="{{ admin_asset('vendor/base/vendor.bundle.base.css') }}">
<link rel="stylesheet" href="{!! admin_asset('vendor/select2/css/select2.min.css') !!}">
<link rel="stylesheet" href="{!! admin_asset('vendor/switchery/css/switchery.min.css') !!}">
<link rel="stylesheet" href="{!! admin_asset('vendor/bootstrap-tag/bootstrap-tagsinput.css') !!}">
<link rel="stylesheet" href="{!! admin_asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') !!}">
<link rel="stylesheet" href="{{ admin_asset('vendor/timepicker/jquery.timepicker.min.css') }}">
<link rel="stylesheet" href="{{ admin_asset('vendor/simplebar/simplebar.css') }}">
<!-- endinject -->

<link rel="stylesheet" href="{{ admin_asset('vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ admin_asset('css/style.min.css') }}">
<link class="main-stylesheet" href="{!! admin_asset('css/additional.css?v=1.2.0') !!}" rel="stylesheet" type="text/css" />

@if(setting('admin.favicon'))
<link rel="icon" type="image/png" href="{{ storage_url(thumbnail(setting('admin.favicon'), 'small')) }}" />
@else
<link rel="icon" type="image/png" href="{{ asset('admin_theme/img/logo.png') }}" />
@endif
<script src="{{ admin_asset('vendor/base/jquery-3.4.1.min.js') }}"></script>
@stack ('style')
@stack ('styles')