@extends ('main::master')

@push ('style')
<link rel="stylesheet" href="{{ admin_asset('vendor/jquery-nestable/jquery.nestable.min.css') }}">
<link rel="stylesheet" href="{{ admin_asset('css/navigation.css') }}">
@endpush

@section ('content')
<h2>{{ $title }} "{{ $data->group_name }}"</h2>

<a href="{{ route('admin.navigation.index') }}" class="btn btn-sm btn-secondary">Back</a>

<div class="card card-body mt-3 nav-holder">
	
	<a href="#" class="btn btn-primary mb-3" data-toggle="modal" data-target="#navigationModal"><i class="fa fa-plus"></i> Add New Menu</a>

	<div class="navigation-list-holder">
		@include ('navigation::partials.navigation-item-list')
	</div>

</div>
@stop

@push ('modal')
	@include ('navigation::partials.modal-crud')
@endpush

@push ('script')
<script src="{{ admin_asset('vendor/jquery-nestable/jquery.nestable.min.js') }}"></script>
<script>
$(function(){
	loadNestable();

	$(document).on('change', '.action-toggle', function(){
		initAfterLoadModal();
	});

	$(document).on('click', '.btn-update-menu', function(){
		target_id = $(this).attr('data-navigation-item-id');
		$("#page-loader").show();
		$.ajax({
			url : window.BASE_URL + '/navigation-form/' + target_id,
			type : 'GET',
			dataType : 'html',
			success : function(resp){
				$("#navigationModal .default-modal-content").html(resp);
				$("#navigationModal").modal('show');
				$("#page-loader").hide();
				initAfterLoadModal();
			},
			error : function(resp){
				swal('error', ['Sorry, we cannot process your request right now']);
				$("#page-loader").hide();
			}
		});
	});

	$(document).on('click', '.reorder-btn', function(){
		reorder_data = window.JSON.stringify($(".nav-nestable").nestable('serialize'));
		$("#page-loader").show();
		$.ajax({
			url : window.BASE_URL + '/navigation-item/reorder/{{ $id }}',
			type : 'POST',
			dataType : 'json',
			data : {
				_token : window.CSRF_TOKEN,
				data : reorder_data
			},
			success : function(resp){
				toggleSuccess();
				$(".reorder-btn").slideUp();
				refreshNestable();
			},
			error : function(resp){
				$("#page-loader").hide();
				swal('error', ['Sorry, we cannot reorder the data right now. Please try again later']);
			}
		});
	});

	initAfterLoadModal();

});

function afterDeleteNavigation(){
	//after delete called
	refreshNestable();
}

function refreshNestable(){
	$("#page-loader").show();
	$(".navigation-list-holder").html('');
	$.ajax({
		url : window.BASE_URL + '/navigation-refresh/{{ $id }}',
		dataType : 'html',
		success : function(resp){
			$(".navigation-list-holder").html(resp);
			loadNestable();
			$("#page-loader").hide();
		},
		error : function(resp){
			swal('error', ['Sorry, we cannot refresh the navigation']);
			$("#page-loader").hide();
		}
	});
}

function loadNestable(){
	$(".nav-nestable").each(function(){
		$(this).nestable({
			maxDepth : parseInt($(this).attr('data-level'))
		}).on('change', function(){
			serializeGroup($(this).attr('data-group'));
		});
		serializeGroup($(this).attr('data-group'), true);
	});	
}

function initAfterLoadModal(){
	sel = $(".action-toggle").find('option:selected');
	$(".action-type-value").slideUp();
	$(".action-type-value[data-type='"+sel.attr('data-target')+"']").slideDown();

	$(".select-icon").select2({
		templateSelection : formatIcons,
		templateResult : formatIcons,
	});
}

function formatIcons(icon){
	return $('<span><i class="fa fa-fw '+ $(icon.element).data('icon') +'"></i> '+ icon.text +'</span>');
}

function serializeGroup(group_id, first_try){
	first_try = first_try || false;

	inputInstance = $("input[name='order-data'][data-group='"+group_id+"']");
	oldVal = inputInstance.val();

	json = $(".nav-nestable[data-group='"+group_id+"']").nestable('serialize');
	jsonData = window.JSON.stringify(json);
	inputInstance.val(jsonData);

	if(!first_try){
		if(oldVal != jsonData){
			//ada perubahan order, munculkan tombol show reorder button
			$(".reorder-btn[data-group='"+group_id+"']").slideDown();
		}
	}
}	
</script>
@endpush