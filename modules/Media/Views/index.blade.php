@extends ('main::master')

@include ('main::assets.fancybox')
@include ('media::inc.dropzone-asset')

@section ('content')
<h2 class="mb-3">{{ $title }}</h2>

<?php
$hash = sha1(rand(1, 10000) . uniqid() . time());
?>
<div class="image-input-holder card" data-hash="#{{ $hash }}">
	<input type="hidden" name="image" value="" class="form-control" id="{{ $hash }}">
	<div class="card-body text-center">
		<img src="{{ admin_asset('img/broken-image.jpg') }}" alt="Image Example" style="width:100%;">
		<div class="closer">&times;</div>
	</div>
	<div class="card-footer">
		<button class="btn btn-block btn-primary" id="media-set-image" data-target="#{{ $hash }}">Set Image</button>
	</div>
</div>





<div class="modal fade fill-in" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
	<button type="button" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="text-left p-b-5 default-modal-title">Choose Image</h5>
			</div>
			<div class="modal-body default-modal-content">

				<ul class="nav nav-tabs" id="media-tab" role="tablist">
				  <li class="nav-item">
				    <a class="nav-link active" id="select-uploaded-tab" data-toggle="tab" href="#select-uploaded" role="tab" aria-controls="select-uploaded" aria-selected="true"><i class="fa fa-table fa-fw"></i> Select Uploaded</a>
				  </li>
				  <li class="nav-item">
				    <a class="nav-link" id="manual-tab" data-toggle="tab" href="#manual" role="tab" aria-controls="manual" aria-selected="false"><i class="fa fa-upload fa-fw"></i> Upload Manually</a>
				  </li>
				</ul>
				<div class="tab-content" id="myTabContent">
				  <div class="tab-pane fade show active" id="select-uploaded" role="tabpanel" aria-labelledby="select-uploaded-tab">
				  	<div class="card card-body">
							<div class="media-holder"></div>
							@include ('media::partials.media-detail')
				  	</div>
				  </div>
				  <div class="tab-pane fade" id="manual" role="tabpanel" aria-labelledby="manual-tab">
				  	<div class="card card-body">
					  	@include ('media::inc.dropzone-multiple')
				  	</div>
				  </div>
				</div>

			</div>
		</div>
	</div>
</div>

@stop

@push ('script')
<script>
var DEFAULT_THUMBNAIL = '{{ admin_asset('img/broken-image.jpg') }}';
$(function(){
	$("#media-set-image").on('click', function(){
		openPage();
		$("#mediaModal").attr('data-target', $(this).attr('data-target'));
		$("#mediaModal").modal('show');
	});


	$(document).on('click', ".media-container .image-thumb-selection", function(){
		$(".media-detail .image-thumbnail").attr('src', $(this).attr('data-thumb'));
		$(".media-detail .filename").html($(this).attr('data-filename'));
		$(".media-detail .url a").html($(this).attr('data-origin'));
		$(".media-detail .url a").attr('href', $(this).attr('data-origin'));
		$("#media-selected-id").val($(this).attr('data-id'));
		$(".media-detail").show();
	});

	$(document).on('click', ".media-detail .closer", function(){
		$(".media-detail").fadeOut(250);
	});

	$(document).on('click', '#set-this-image', function(e){
		e.preventDefault();

		let resp = new Object;
		resp['id'] = $("#media-selected-id").val();
		resp['thumb'] = $('.media-detail .thumbnail_size').val();

		target = $('#mediaModal').attr('data-target');
		$('.image-input-holder[data-hash="'+target+'"] img').attr('src', $(".media-detail .image-thumbnail").attr('src'));
		$(target).val(window.JSON.stringify(resp));
		$(".media-detail").fadeOut();
		$("#mediaModal").modal('hide');
	});

	$(document).on('click', '.image-input-holder .closer', function(){
		ih = $(this).closest('.image-input-holder');
		$('input' + ih.attr('data-hash')).val('');
		ih.find('img').attr('src', window.DEFAULT_THUMBNAIL);
	});

});

function openPage(page){
	page = page || 1;
	target_url = window.BASE_URL + '/media/load?page=' + page;

	$("#page-loader").show();
	$.ajax({
		url : target_url,
		type : 'GET',
		dataType : 'html',
		success : function(resp){
			$(".media-holder").html(resp);
			$("#page-loader").hide();

			thumb_width = $(".media-holder img").width();
			$(".media-holder img").height(thumb_width);

		},
		error : function(resp){
			swal('error', ['Failed to load the media']);
			$("#page-loader").hide();
		}
	});
}

</script>
@endpush