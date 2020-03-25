@extends ('layouts.master')

@section ('content')
<section id="content">
	<div class="content-wrap">
		<div class="container">
			<h2 class="py-4">{{ $title }}</h2>

			@include ('pages.blog-filter')
			<div class="post-holder">
				<div class="text-center page-loader">
					<i class="fa fa-spinner fa-spin"></i>
				</div>
				<div class="post-content">
					{!! $data !!}
				</div>
			</div>
		</div>
	</div>
</section>
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

	$(document).on('click', '.reset-post-filter', function(e){
		e.preventDefault();
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