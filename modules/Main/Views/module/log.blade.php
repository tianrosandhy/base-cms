@extends ('main::master')

@section ('content')

<h3>{!! $title !!}</h3>

<div class="padd">
	<form action="">
		<select name="active_log" class="form-control select2" onchange="this.form.submit()">
			<option value="">- Choose Log Filename -</option>
			@foreach($available_log as $logs)
			<option value="{{ $logs }}" {{ $logs == $active_log ? 'selected' : '' }}>{{ $logs }}</option>
			@endforeach
		</select>
	</form>
</div>

@if($active_log)
<div class="panel">
	<div style="padding:1em 0">
		<a href="{{ url()->route('admin.log.export') }}?active_log={{ $active_log }}" class="btn btn-primary btn-block">Export Log</a>
	</div>
</div>
@endif

@stop