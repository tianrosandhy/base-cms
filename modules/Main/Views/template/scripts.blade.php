<!-- plugins:js -->
<script src="{{ admin_asset('vendor/base/vendor.bundle.base.js') }}"></script>
<script src="{{ admin_asset('vendor/bootstrap-datetimepicker/moment.js') }}"></script>
<script src="{!! admin_asset('js/jquery-debounce.js') !!}" type="text/javascript"></script>
<script src="{!! admin_asset('js/modernizr.custom.js') !!}" type="text/javascript"></script>
<script src="{!! admin_asset('vendor/select2/js/select2.min.js') !!}"></script>
<script src="{!! admin_asset('vendor/switchery/js/switchery.min.js') !!}"></script>
<script src="{!! admin_asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') !!}"></script>
<script src="{!! admin_asset('vendor/bootstrap-tag/bootstrap-tagsinput.min.js') !!}"></script>
<script src="{{ admin_asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ admin_asset('vendor/tinymce/jquery.tinymce.min.js') }}"></script>
<script src="{{ admin_asset('vendor/timepicker/jquery.timepicker.min.js') }}"></script>
<script src="{{ admin_asset('js/jquery.mask.min.js') }}"></script>
<script src="{{ admin_asset('vendor/simplebar/simplebar.js') }}"></script>
<!-- inject:js -->
<script src="{{ admin_asset('js/off-canvas.js') }}"></script>
<script src="{{ admin_asset('js/hoverable-collapse.js') }}"></script>
<script src="{{ admin_asset('js/template.js') }}"></script>

<script src="{{ admin_asset('js/additional.js?v=1.0.1') }}"></script>
<!-- endinject -->

<script>
var CSRF_TOKEN = '{{ csrf_token() }}';
var CURRENT_URL = '{{ url()->current() }}';
var BASE_URL = '{{ admin_url() }}';
var SITE_URL = '{{ url('/') }}';
var STORAGE_URL = '{{ storage_url() }}';
</script>
@stack ('script')
@stack ('scripts')