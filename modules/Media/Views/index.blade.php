@extends ('main::master')

@include ('main::assets.fancybox')
@include ('media::inc.dropzone-asset')

@section ('content')
<h2 class="mb-3">{{ $title }}</h2>

<?php
$hash = sha1(rand(1, 10000) . uniqid() . time());
?>
<input type="text" name="image" value="" class="form-control" id="{{ $hash }}">
<div class="padd">
	<button class="btn btn-primary" id="media-set-image" data-target="#{{ $hash }}">Set Image</button>
</div>

<div class="modal fade fill-in" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
	<button type="button" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<div class="modal-dialog modal-lg">
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
var CURRENT_SHORTLINK = null;
$(function(){
	$("#media-set-image").on('click', function(){
		openPage();
		$("#mediaModal").attr('data-target', $(this).attr('data-target'));
		$("#mediaModal").modal('show');
	});


	$(document).on('click', '.open-directory', function(e){
		e.preventDefault();
		shortlink = $(this).attr('shortlink');
		CURRENT_SHORTLINK = shortlink;
		openPage(shortlink);
	});

	$(document).on('change', '.file-checker', function(){
		manageToggleAllButton();
	});

	$(document).on('click', '.btn-select-all-file', function(e){
		e.preventDefault();
		$(".file-checker").each(function(){
			$(this).prop('checked', true);
		});
	});
	
	$(document).on('click', '.btn-unselect-all-file', function(e){
		e.preventDefault();
		$(".file-checker").each(function(){
			$(this).prop('checked', false);
		});
		manageToggleAllButton();
	});
	
	$(document).on('click', '.btn-remove-selected', function(e){
		e.preventDefault();		
		output = '<p>The file checked will be deleted. Are you sure?</p><button class="btn btn-primary" data-dismiss="modal">Cancel</button> <button class="btn btn-danger" onclick="runRemoveBatchFile()">Yes, Delete</button>';
		swal('Run Batch Delete?', [output]);

	});
});

function runRemoveBatchFile(){
	ids = [];
	$(".file-checker").each(function(){
		if($(this).is(':checked')){
			ids.push($(this).val());
		}
	});

	$.ajax({
		url : window.BASE_URL + '/media/delete',
		type : 'POST',
		dataType : 'json',
		data : {
			_token : window.CSRF_TOKEN,
			data : ids
		},
		success : function(resp){
			toggleSuccess();
			openPage(window.CURRENT_SHORTLINK);
		},
		error : function(resp){
			$("#page-loader").hide();
			swal('error', ['Sorry, we cannot remove the files']);
		}
	});

	console.log(ids);
}

function openPage(shortlink){
	target_url = shortlink ? window.BASE_URL + '/media/load?shortlink=' + shortlink : window.BASE_URL + '/media/load';

	$("#page-loader").show();
	$.ajax({
		url : target_url,
		type : 'GET',
		dataType : 'html',
		success : function(resp){
			$(".media-holder").html(resp);
			$("#page-loader").hide();
			$('[data-toggle="tooltip"]').tooltip();
		},
		error : function(resp){
			swal('error', ['Failed to load the media']);
			$("#page-loader").hide();
		}
	});
}


function manageToggleAllButton(){
	cond = false;
	$("input.file-checker").each(function(){
		if($(this).prop('checked')){
			cond = true;
		}
	});

	if(cond){
		$(".media-control").slideDown();
	}
	else{
		$(".media-control").slideUp();
	}
}

</script>
@endpush