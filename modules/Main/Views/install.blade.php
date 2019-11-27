<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>TianRosandhy CMS Installer</title>
	<link rel="stylesheet" href="{{ asset('admin_theme/css/style.css') }}">
	<link rel="stylesheet" href="{{ asset('admin_theme/css/additional.css') }}">
</head>
<body>

<div class="container">
	<center style="padding-top:2em;">
		<img src="{{ asset('admin_theme/img/logo.png') }}" alt="CMS TianRosandhy" style="height:40px">
		<h2 class="display-2 mt-3">CMS Install</h2>
	</center>

	<p>Welcome to CMS installation page. Please fill the steps below to complete the CMS installation.</p>

	
	@if(!$has_install)
		@if($db)

			@if(session('error'))
			<div class="alert alert-danger">{{ session('error') }}</div>
			@elseif(session('success'))
			<div class="alert alert-success">{{ session('success') }}</div>
			@endif

			<div class="alert alert-danger">
				<strong class="text-uppercase">Database Connection Error</strong>
				<br>
				<p>Manage your database connection in <em><u>.env</u></em> file. Please make sure the database name provided is exists. The problem usually consist within these : </p>
				<ul>
					<li>Invalid database connection in .env</li>
					<li>Database is still not created</li>
					<li>Different database host or port. The default is 127.0.0.1:3306</li>
					<li>You just have a bad luck</li>
				</ul>
			</div>
			@if($env)
			<form action="" method="post">
				{{ csrf_field() }}
				<table class="table table-sm">
					<tbody>
						<?php
						$used_param = config('module-setting.install.used_env');
						?>
						@foreach($env as $param)
						<?php
						$pecah = explode('=', $param);
						if(count($pecah) <> 2){
							continue;
						}
						$key = $pecah[0];
						$value = $pecah[1];
						if(!in_array($key, $used_param)){
							continue;
						}
						?>
						<tr>
							<td>{{ $key }}</td>
							<td><input type="text" name="{{ $key }}" value="{{ $value }}" class="form-control" placeholder="null"></td>
						</tr>
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td><button class="btn btn-primary">Update Database Config</button></td>
						</tr>
					</tfoot>
				</table>
			</form>
			@else
			<div class="alert alert-warning">We dont have permission to access your <strong>.env</strong> file. Please update them manually.</div>
			@endif
		@endif
		
		<br>
		<br>
		<br>

		@if(!$db)

		@if(session('error'))
		<div class="alert alert-danger">{{ session('error') }}</div>
		@elseif(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
		@endif
		<div class="alert alert-primary">Fill these form below to install the CMS</div>

		<form action="" method="post">
			{{ csrf_field() }}
			<div class="form-group">
				<label>Site Name *</label>
				<input type="text" class="form-control" name="title" value="{{ old('title') }}">
			</div>
			<div class="form-group">
				<label>Site Description</label>
				<input type="text" class="form-control" name="description" value="{{ old('description') }}">
			</div>
			<br>
			<br>
			<div class="form-group">
				<label>Default Admin Full Name  *</label>
				<input type="text" name="name" class="form-control" value="{{ old('name') }}">
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Default Admin Email *</label>
						<input type="email" name="email" class="form-control" value="{{ old('email') }}">
					</div>
					
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Default Admin Password *</label>
						<input type="password" name="password" class="form-control">
					</div>
					
				</div>
			</div>
			<button class="btn btn-primary">Run Installation</button>
		</form>
		@endif

		<br>
		<br>
		<br>
		<br>
		<br>
	@else
	<div class="alert alert-info">The CMS has been installed.</div>
	<a href="{{ url('/') }}" class="btn btn-primary">Go To Homepage</a>
	<a href="{{ route('admin.splash') }}" class="btn btn-success">Go To Admin Panel</a>
	@endif

</div>

</body>
</html>