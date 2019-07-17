@extends ('main::master')
@include ('main::assets.dropzone')

@section ('content')
<h3>My Profile</h3>

<div class="card card-body">
	<form action="" method="post" autocomplete="off">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group custom-form-group searchable">
					<label>Full Name</label>
					<input type="text" name="name" class="form-control" value="{{ admin_data('name') }}">
				</div>
				<div class="form-group custom-form-group searchable">
					<label>Email</label>
					<input type="email" name="email" class="form-control" value="{{ admin_data('email') }}" autocomplete="off">
				</div>

				<div class="change-pass">
					<div class="btn btn-sm btn-danger btn-change-pass">Change Password</div>
				</div>
				<div class="pass-toggle" style="display:none; padding:1em 0;">
					<div class="form-group custom-form-group searchable">
						<label>Password</label>
						<input data-password type="text" name="password" class="form-control" placeholder="Keep blank if you dont want to change" autocomplete="off">
					</div>
					<div class="form-group custom-form-group searchable">
						<label>Repeat Password</label>
						<input data-password type="text" name="password_confirmation" class="form-control">
					</div>
				</div>

				
				<div style="padding:2em 0;">
					<button class="btn btn-primary" type="submit">Update Profile</button>
				</div>
			</div>
			<div class="col-sm-4">
				@include ('main::inc.dropzone', [
					'value' => admin_data('image')
				])
			</div>
		</div>

	</form>

</div>
@stop

@push ('script')
<script>
$(function(){
	$(".btn-change-pass").on('click', function(){
		$(".pass-toggle").slideDown();
		$(".btn-change-pass").slideUp();

		$("input[data-password]").attr('type', 'password');
	});
});
</script>
@endpush