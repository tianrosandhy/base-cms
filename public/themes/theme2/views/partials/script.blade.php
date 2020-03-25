@stack ('modal')
<script>
window.CSRF_TOKEN = '{{ csrf_token() }}';
window.BASE_URL = '{{ url('/') }}';
</script>
<script src="{{ theme_asset('web/assets/jquery/jquery.min.js') }}"></script>
<script src="{{ theme_asset('popper/popper.min.js') }}"></script>
<script src="{{ theme_asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ theme_asset('smoothscroll/smooth-scroll.js') }}"></script>
<script src="{{ theme_asset('dropdown/js/nav-dropdown.js') }}"></script>
<script src="{{ theme_asset('dropdown/js/navbar-dropdown.js') }}"></script>
<script src="{{ theme_asset('tether/tether.min.js') }}"></script>
<script src="{{ theme_asset('parallax/jarallax.min.js') }}"></script>
<script src="{{ theme_asset('viewportchecker/jquery.viewportchecker.js') }}"></script>
<script src="{{ theme_asset('bootstrapcarouselswipe/bootstrap-carousel-swipe.js') }}"></script>
<script src="{{ theme_asset('mbr-clients-slider/mbr-clients-slider.js') }}"></script>
<script src="{{ theme_asset('mbr-popup-btns/mbr-popup-btns.js') }}"></script>
<script src="{{ theme_asset('touchswipe/jquery.touch-swipe.min.js') }}"></script>
<script src="{{ theme_asset('toastr/toastr.min.js') }}"></script>
<script src="{{ theme_asset('theme/js/script.js') }}"></script>

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