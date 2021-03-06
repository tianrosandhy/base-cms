@extends ('main::master')

@section ('content')
<div class="header-box">
	<h3 class="display-4 mb-3">Role Permission Management</h3>

	<div class="padd">
		@if(has_access('admin.permission.store'))
		<a href="#" class="btn btn-primary add-new-role" data-toggle="modal" data-target="#permissionModal">Add New Role</a>
		@endif
	</div>
</div>

<div class="content-box">
	<div class="card card-body ov">
		<table class="table data-table">
			<thead>
				<tr>
					<th>Priviledge Name</th>
					<th>Permissions</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($structure->role_list as $row)
				<tr class="close-target">
					<td>{{ str_repeat('*', $row['level']) .' '.  $row['label'] }}</td>
					<td>
						@if($row['is_sa'])
						<div class="alert alert-info">
							Superadmin can access anything
						</div>
						@else
							@if(has_access('admin.permission.manage'))
							<a href="#" class="btn btn-default btn-block btn-secondary btn-show-permission" data-target="{{ admin_url('setting/show-permission/'.$row['id']) }}"><i class="fa fa-cog"></i> Manage Permissions</a>
							@endif
						@endif
					</td>
					<td>
						@if(has_access('admin.permission.update'))
						<a href="#" class="btn btn-primary edit-permission" data-target="{{ url()->route('admin.permission.update', ['id' => $row['id']]) }}" data-title="{{ $row['label'] }}" data-issa="{{ $row['is_sa'] }}" data-owner="{{ $row['role_owner'] }}">Edit</a>
						@endif
						@if(!$row['is_sa'])
							@if(has_access('admin.permission.delete'))
							<a href="{{ url()->route('admin.permission.delete', ['id' => $row['id']]) }}" class="btn btn-danger delete-button" >Delete</a>
							@endif
						@endif
					</td>
				</tr>
				@endforeach

				@if(count($structure->role_list) == 0)
				<tr>
					<td colspan="3">
						<div class="alert alert-warning">
							You still not have a child priviledge. 
							@if(has_access('admin.permission.store'))
							<a href="#" class="btn btn-warning" data-toggle="modal" data-target="#permissionModal">Create New Role</a>
							@endif
						</div>
					</td>
				</tr>
				@endif
			</tbody>
		</table>	
	</div>	
</div>


@stop

@push ('modal')
<div class="modal fade slide-right" id="permissionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
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
								<div class="form-group" data-setrole>
									<label>Set this role as children of</label>
									<select name="role_owner" class="form-control select2">
										<option value="">No Owner</option>
										@foreach($structure->dropdown_list as $id_role => $label_role)
										<option value="{{ $id_role }}">{{ $label_role }}</option>
										@endforeach
									</select>
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
		$("#permissionModal form .form-group input[name='name']").val($(this).attr('data-title'));
		$("#permissionModal form .form-group select[name=role_owner]").val($(this).attr('data-owner')).trigger('change.select2');
		$("#permissionModal form button").html('Update Role');
		$("#permissionModal").modal('show');

		if($(this).attr('data-issa') == 1){
			$("#permissionModal form [data-setrole]").hide();
		}
		else{
			$("#permissionModal form [data-setrole]").show();
		}
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