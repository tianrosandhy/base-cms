@extends ("main::master")
@include ('main::assets.dropzone')
<?php
if(!isset($multi_language)){
	$multi_language = false; //default fallback
}
?>
@section ('content')
<div class="header-box">
	<h3 class="display-4 mb-3">{{ $title }}</h3>
	<div class="padd">
		<a href="{{ url()->route($back) }}" class="btn btn-sm btn-default btn-secondary">&laquo; Back</a>
	</div>
</div>

@include ('main::inc.lang-switcher', [
	'model' => $forms->model,
	'reload' => false
])

<div class="content-box">
	<form action="" method="post">
		{{ csrf_field() }}
		<div class="card card-block card-body">
			<div class="row">
				<div class="col-sm-8">
					<div class="form-group custom-form-group">
						<label>Full Name</label>
						<input type="text" class="form-control" name="name" value="{{ old('name', (isset($data->name) ? $data->name : null)) }}">
					</div>
					<div class="form-group custom-form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" value="{{ old('email', (isset($data->email) ? $data->email : null)) }}">
					</div>

					@if(isset($data->password))
					<div>
						<div class="padd">
							<a href="#" class="btn btn-secondary btn-sm change-pass">Change Password</a>
						</div>
						<div class="password-toggle" style="display:none;">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group custom-form-group">
										<label>New Password</label>
										<input type="text" as-password name="password" class="form-control" maxlength="50">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group custom-form-group">
										<label>Repeat Password</label>
										<input type="text" as-password name="password_confirmation" class="form-control" maxlength="50">
									</div>
								</div>
							</div>
						</div>
					</div>
					@else
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group custom-form-group">
								<label>Password</label>
								<input type="password" name="password" class="form-control" maxlength="50">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group custom-form-group">
								<label>Repeat Password</label>
								<input type="password" name="password_confirmation" class="form-control" maxlength="50">
							</div>
						</div>
					</div>
					@endif

					<?php
					$priv = collect($forms->structure)->where('field', 'role_id')->first();
					$selc = old('role_id', isset($data->role_id) ? $data->role_id : null);
					$is_sa = isset($data->roles->is_sa) ? $data->roles->is_sa : false;

					$priv_output = isset($priv->data_source->output) ? $priv->data_source->output : (isset($priv->data_source) ? $priv->data_source : []);
					?>
					@if(!empty($priv_output) && !$is_sa)
					@if(empty($data->id) || $data->id <> admin_guard()->user()->id)
					<div class="form-group custom-form-group">
						<label>Priviledge</label>
						<select name="role_id" class="form-control">
							<option value="">- No Priviledge -</option>
							@foreach($priv_output as $idp => $valp)
							<option value="{{ $idp }}" {{ $idp == $selc ? 'selected' : '' }}>{{ $valp }}</option>
							@endforeach
						</select>
					</div>
					@endif
					@endif

					@if(!$is_sa)
					@if($data->id <> admin_guard()->user()->id)
					<div class="form-group custom-form-group">
						<label>User Status</label>
						<select name="is_active" class="form-control">
							<option value="1" {{ isset($data->is_active) ? ($data->is_active == 1 ? 'selected' : '') : '' }}>Active</option>
							<option value="0" {{ isset($data->is_active) ? ($data->is_active == 0 ? 'selected' : '') : '' }}>Pending</option>
							@if(isset($data))
							@if($data->is_active))
							<option value="9" {{ $data->is_active == 9 ? 'selected' : '' }}>Blocked</option>
							@endif
							@endif
						</select>
					</div>
					@endif
					@endif

				</div>
				<div class="col-sm-4">
					<div class="form-group custom-form-group">
						<label>User Profile Picture</label>
						{!! MediaInstance::input('image', old('image', (isset($data->image) ? $data->image : null))) !!}
					</div>
				</div>
			</div>

			<div class="padd">
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
				
		</div>
	</form>	
</div>


@stop

@push ('script')
{!! MediaInstance::assets() !!}

<script>
$(function(){
	$('.radio-box').each(function(){
		setFormGroupBg($(this).find('input:checked'));
	});

	$(document).on('change', '.radio-box input:checked', function(){
		setFormGroupBg($(this));
	});

	$(document).on('click', ".change-pass", function(){
		$(this).closest('div').slideUp();
		$('.password-toggle').slideDown();
		$('[as-password]').attr('type', 'password');
	});
});

function setFormGroupBg(instance){
	boxval = instance.val();
	if(boxval == 0){
		instance.closest('.radio-box').addClass('danger');
		instance.closest('.radio-box').removeClass('success');
	}
	else if(boxval == 1){
		instance.closest('.radio-box').addClass('success');
		instance.closest('.radio-box').removeClass('danger');
	}
}


</script>
@endpush