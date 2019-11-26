@extends ('main::master')

@include ('main::assets.fancybox')

@section ('content')
<h2 class="mb-3">{{ $title }}</h2>

<div class="media-holder"></div>
@stop

@push ('script')
<script>
var CURRENT_SHORTLINK = null;
$(function(){
	openPage();
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