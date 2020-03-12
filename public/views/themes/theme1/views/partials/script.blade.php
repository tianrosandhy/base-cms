@stack ('modal')
<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';
window.BASE_URL = '{{ url('/') }}';
</script>
<script src="{{ theme_asset('js/jquery.js') }}"></script>
<script src="{{ theme_asset('js/plugins.js') }}"></script>
<script src="{{ theme_asset('js/functions.js') }}"></script>
<script src="{{ theme_asset('js/additional.js') }}"></script>
@if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SECRET_KEY'))
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script>
$(function(){
	@if(isset($errors))
		@if($errors->any())
			@foreach($errors->getMessages() as $error)
			toastr.error("{{ $error[0] }}");
			@endforeach
		@endif
	@endif

	@if(session('success'))
		toastr.success("{{ session('success') }}", "Success");
	@endif
	@if(session('error'))
		toastr.error("{{ session('error') }}", "Error");
	@endif
});
</script>
@stack ('script')