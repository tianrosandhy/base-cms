@extends ('main::master')

@push ('style')
<style>
	.widget{
		border-top:5px solid #00AAFF;
		box-shadow:0px 0px 20px #ccc;
	}
</style>
@endpush

@section ('content')
<h3>Dashboard</h3>

<div class="row">
	@if(Route::has('admin.post.index'))
	<div class="col-sm-4">
		<div class="card card-body widget">
			<h2>{{ $post }}</h2>
			<p>Posts Data</p>
			<a href="{{ url()->route('admin.post.index') }}" class="btn btn-primary">Go To Posts</a>
		</div>
	</div>
	@endif
	@if(Route::has('admin.project.index'))
	<div class="col-sm-4">
		<div class="card card-body widget">
			<h2>{{ $project }}</h2>
			<p>Projects Data</p>
			<a href="{{ url()->route('admin.project.index') }}" class="btn btn-primary">Go To Projects</a>
		</div>
	</div>
	@endif
	@if(Route::has('admin.user.index'))
	<div class="col-sm-4">
		<div class="card card-body widget">
			<h2>{{ $user }}</h2>
			<p>Users Data</p>
			<a href="{{ url()->route('admin.user.index') }}" class="btn btn-primary">Go To Users</a>
		</div>
	</div>
	@endif
</div>
@stop