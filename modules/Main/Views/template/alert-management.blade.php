@if(isset($errors) || session('success') || session('error'))
@if($errors->any() || session('success') || session('error'))
<div class="alert-box" style="padding:.5em; margin-bottom:2em; position:fixed; top:4rem; right:.5em; z-index:9999; text-align:right;">
	@foreach($errors->all() as $err)
		<?php
		$error = str_replace('.'.config('cms.lang.default'), '', $err);
		?>
		<div class="alert alert-danger">{{ $error }}</div>
	@endforeach
	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	@if(session('error'))
		<div class="alert alert-danger">{{ session('error') }}</div>
	@endif
	<a href="#" class="btn btn-secondary btn-sm dismiss-alert"><i class="fa fa-times"></i> Dismiss Message</a>
</div>
<script>
$(function(){
	if($(".alert-box").length > 0){
		setTimeout(function(){
			hideAlertBox();
		}, 20000);
	}
	$(".dismiss-alert").on('click', function(e){
		e.preventDefault();
		hideAlertBox();
	});
});

function hideAlertBox(){
	$(".alert-box").slideUp(300);
	setTimeout(function(){
		$(".alert-box").remove();
	}, 300);
}
</script>
@endif
@endif