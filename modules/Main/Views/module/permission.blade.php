@extends ('main::master')

@section ('content')
<h3>Role Permission Management</h3>

<div class="padd">
	<a href="#" class="btn btn-primary add-new-role" data-toggle="modal" data-target="#permissionModal">Add New Role</a>
</div>

<div class="card card-body">
	<table class="table data-table">
		<thead>
			<tr>
				<th>Priviledge Name</th>
				<th>Permissions</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($lists as $row)
			<tr class="close-target">
				<td>{{ $row->name }}</td>
				@if($row->is_sa)
				<td colspan="2">
					<div class="alert alert-success">All Permission Granted</div>
				</td>
				@else
				<td>
					<a href="" class="btn btn-default btn-block btn-secondary btn-show-permission" data-target="{{ admin_url('setting/show-permission/'.$row->id) }}"><i class="fa fa-cog"></i> Manage Permissions</a>
				</td>
				<td>
					<a href="" class="btn btn-primary edit-permission" data-target="{{ url()->route('admin.permission.update', ['id' => $row->id]) }}" data-title="{{ $row->name }}">Edit</a>
					<a href="{{ url()->route('admin.permission.delete', ['id' => $row->id]) }}" class="btn btn-danger delete-button" >Delete</a>
				</td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>	
</div>

@stop

@push ('modal')
<div class="modal fade slide-right" id="permissionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content-wrapper">
			<div class="modal-content">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i></button>
				<div class="container-xs-height full-height">
					<div class="row-xs-height">
						<div class="modal-body col-xs-height col-middle text-center">
							<form action="" method="post">
								{{ csrf_field() }}
								<div class="form-group">
									<label>Type the role name below</label>
									<input type="text" class="form-control" name="name">
								</div>
								<div class="padd">
									<button class="btn btn-primary">Add Role</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endpush

@push ('script')
<script>
$(function(){
	$('body').on('click', ".btn-show-permission", function(e){
		e.preventDefault();
		$.ajax({
			url : $(this).attr('data-target'),
			type : 'POST',
			dataType : 'html',
			data : {
				_token : CSRF_TOKEN
			},
			success : function(resp){
				defaultModal('Permission Lists', resp);
				loadCheckEvent();
			},
			error : function(resp){
				error_handling(resp);
			}
		});
	});

	$("body").on('change', '.priviledge-check input[type=checkbox]', function(){
		inputCheckEvent($(this));
		groupCheckCondition();
	});

	$("body").on('change', '.group-checkbox', function(){
		items = $(this).closest('td').next('td').find('.priviledge-check');
		condition = $(this).is(':checked');
		$.each(items, function(){
			$(this).find('input[type=checkbox]').prop('checked', condition).change();
		});
	});


	$(".add-new-role").on('click', function(){
		$("#permissionModal form").attr('action', '');
		$("#permissionModal form .form-group input").val('');
		$("#permissionModal form button").html('Add Role');
	});
	$(".edit-permission").on('click', function(e){
		e.preventDefault();
		$("#permissionModal form").attr('action', $(this).attr('data-target'));
		$("#permissionModal form .form-group input").val($(this).attr('data-title'));
		$("#permissionModal form button").html('Update Role');
		$("#permissionModal").modal('show');
	});

});

function loadCheckEvent(){
	$(".priviledge-check").each(function(){
		input = $(this).find('input[type=checkbox]');
		inputCheckEvent(input);
	});
	groupCheckCondition();
}

function groupCheckCondition(){
	$(".group-checkbox").each(function(){
		items = $(this).closest('td').next('td').find('.priviledge-check');
		cond = true;
		$.each(items, function(){
			cond = cond && $(this).find('input[type=checkbox]').prop('checked');
		});

		if(cond == true){
			$(this).prop('checked', true);
		}
		else{
			$(this).prop('checked', false);
		}
	});
}

function inputCheckEvent(input){
	paren = input.closest('.priviledge-check');
	if(input.is(':checked')){
		paren.addClass('active');
	}
	else{
		paren.removeClass('active');
	}
}
</script>
@endpush