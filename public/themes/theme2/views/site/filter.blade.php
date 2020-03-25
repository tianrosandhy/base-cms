@extends ('layouts.master')
@section ('content')
<div class="container clearfix non-homepage">
	<h2 class="display-header">{{ $title }}</h2>

	@include ('pages.blog-filter')
	<div class="post-container mt-5 p-5">
		<div class="text-center page-loader">
			<i class="fa fa-spinner fa-spin"></i>
		</div>
		<div class="post-content">
			{!! $data !!}
		</div>
	</div>
</div>

@stop

@push ('script')
<script>
$(function(){
	hideLoading();
	$(".blog-filter").on('submit', function(e){
		e.preventDefault();
		showLoading();
		$.ajax({
			url : window.BASE_URL + '/api/post',
			data : $(this).serialize(),
			dataType : 'html',
			success : function(resp){
				hideLoading(resp);
			},
			error : function(resp){
				toastr.error('Sorry, we cannot process your request right now');
				hideLoading();
			}
		});
	});

	$(document).on('click', '.reset-post-filter', function(){
		$(".blog-filter")[0].reset();
		setTimeout(function(){
			$(".blog-filter").submit();
		}, 50);
	});
});

function showLoading(){
	$(".post-content").hide();
	$(".page-loader").fadeIn();
}

function hideLoading(resp){
	$(".page-loader").fadeOut();
	if(resp){
		$(".post-content").html(resp);
	}
	$(".post-content").show();
}
</script>
@endpush