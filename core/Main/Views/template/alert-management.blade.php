@if(isset($errors) || session('success') || session('error'))
@if($errors->any() || session('success') || session('error'))
<script>
toastr.options.timeOut = 10000;
$(function(){
	@foreach($errors->all() as $err)
		<?php
		$error = str_replace('.'.def_lang(), '', $err);
		?>
		toastr["error"]("{{ $error }}");
	@endforeach
	@if(session('success'))
		toastr["success"]("{{ session('success') }}");
	@endif
	@if(session('error'))
		toastr["error"]("{{ session('error') }}");
	@endif
});
</script>
@endif
@endif