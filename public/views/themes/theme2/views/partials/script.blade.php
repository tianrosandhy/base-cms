<script src="{{ asset('styling/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('styling/js/bootstrap.bundle.min.js') }}"></script>
<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';
window.BASE_URL = '{{ url('/') }}';
</script>
<script src="{{ asset('styling/js/additional.js') }}"></script>