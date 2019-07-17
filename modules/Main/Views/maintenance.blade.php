@extends ('main::master')

@push ('style')
<style>
	pre{
		position:relative;
		display:block;
		padding:1em;
		background:#222;
		color:#fff;
		border-radius:4px;
		-moz-border-radius:4px;
	}

	pre code{
		width:100%;
		display:block;
	}
</style>
@endpush

@section ('content')
	<h3>Maintenance Section</h3>
	@if(has_access('admin.maintenance.maintenance') || has_access('admin.maintenance.artisan') || has_access('admin.maintenance.file-clean'))
	<div class="alert alert-warning">
		This is danger section area. If you dont have an idea about what's here, please dont click anything for better purpose.
	</div>
	@else
	<div class="alert alert-warning">
		This is danger section area. Well, you dont have anything to do here because you dont have the priviledge to do so. <a href="#" onclick="history.go(-1)">Back</a>
	</div>
	@endif

	@if(has_access('admin.maintenance.maintenance'))
	<div class="panel panel-body">
		<div class="form-group">
			<label>Site Maintenance Mode</label>
			<div>
				Status : 
				@if(is_maintenance())
				<span class="label label-danger">Enabled</span>
				@else
				<span class="label label-default">Disabled</span>
				@endif
				<br>
				<form action="" method="post">
					{{ csrf_field() }}
					@if(is_maintenance())
					<button class="btn btn-info">Disable Maintenance Mode</button>
					@else
					<button class="btn btn-danger">Enable Maintenance Mode</button>
					@endif
				</form>
			</div>
		</div>
	</div>
	@endif

	@if(has_access('admin.maintenance.artisan'))
	<div class="panel panel-body">
		<div class="form-group">
			<label>Simple Artisan Console Runner</label>
			<div>
				@if(session('artisan'))
				<small>Command result</small>
				<pre><code class="languange-">{!! session('artisan') !!}</code></pre>
				@endif
				<p>Type the artisan command you want to run below.</p>
				<div class="panel panel-block">
					<form action="{{ url()->route('admin.maintenance.artisan') }}" class="form-inline" method="post">
						{{ csrf_field() }}
						<span style="padding:.5em;">php artisan</span>
						<input type="text" class="form-control" name="key">
						<button class="btn btn-danger">Run</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	@endif

@stop