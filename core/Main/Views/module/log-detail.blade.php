@extends (request()->ajax() ? 'main::master-ajax' : 'main::master')
@section ('content')
<div class="header-box">
	<h4 class="display-4">{{ $title }}</h4>
	@if(!request()->ajax())
	<div class="padd">
		<a href="{{ route('admin.log.index') }}" class="btn btn-secondary btn-sm">Back</a>
	</div>
	@endif
</div>
<div class="content-box">
	<div class="form-group custom-form-group">
		<label>URL</label>
		<h5 style="padding:0 .5em"><a href="{{ $data->url }}" target="_blank">{{ $data->url }}</a></h5>
	</div>

	<div class="row">
		<div class="col-sm-6">
			@if($data->is_reported)
			<span class="badge badge-success">Reported at {{ date('d M Y H:i:s', strtotime($data->updated_at)) }}</span>
			@else
			<span class="badge badge-danger">Not Reported Yet</span>
			@endif
		</div>
		<div class="col-sm-6">
			<span class="badge badge-primary">Exception Type : {{ $data->type }}</span>
		</div>
	</div>

	<div class="card card-body mt-3">
		<p class="lead">{{ strlen($data->description) > 0 ? $data->description : 'No description' }}</p>
	</div>

	<div class="form-group custom-form-group mb-3">
		<label>File Path</label>
		<h5 style="padding:0 .5em">{{ $data->file_path }}</h5>
	</div>

	<?php
	$stacktrace = json_decode($data->backtrace, true);
	if(!$stacktrace){
		$stacktrace = [];
	}
	?>
	@if(!empty($stacktrace))
	<div class="form-group custom-form-group">
		<label>Stack Trace</label>
		<div style="padding:0 .5em">
			<ul class="list-group">
				@foreach($stacktrace as $row)
				<li class="list-group-item">
					#{{ $loop->iteration }} {{ isset($row['file']) ? $row['file'] : '-'}}
					<br>
					@if(isset($row['class']))
					<strong>{{ $row['class'] }}</strong>
					@endif
					@if(isset($row['function']))
					method <strong>{{ $row['function'] }}</strong>
					@endif
					@if(isset($row['line']))
						Line {{ $row['line'] }}
					@endif
				</li>
				@endforeach
			</ul>
		</div>
	</div>
	@else
	<div class="alert alert-danger">No stacktrace</div>
	@endif
</div>
@stop