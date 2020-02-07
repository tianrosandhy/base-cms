@stack ('modal')
<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';
window.BASE_URL = '{{ url('/') }}';
</script>
<script src="{{ asset('styling/js/jquery.js') }}"></script>
<script src="{{ asset('styling/js/plugins.js') }}"></script>
<script src="{{ asset('styling/js/functions.js') }}"></script>
<script src="{{ asset('styling/js/additional.js') }}"></script>
@stack ('script')