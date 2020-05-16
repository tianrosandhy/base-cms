@extends ('main::master')

@section ('content')

<div class="header-box">
	<h3 class="display-4 mb-3">{!! $title !!}</h3>

	<div class="padd">
		<form action="">
			<div class="form-group custom-form-group">
				<label>Choose Log Filename</label>
				<select name="active_log" class="form-control select2" onchange="this.form.submit()">
					<option value="">- Choose Log Filename -</option>
					@foreach($available_log as $logs)
					<option value="{{ $logs }}" {{ $logs == $active_log ? 'selected' : '' }}>{{ $logs }}</option>
					@endforeach
				</select>
			</div>

			@if($active_log)
			<div class="panel">
				<div style="padding:1em 0">
					@if(isset($log_size))
					<strong>Log File Size : {{ $log_size }}</strong>
					@endif
					<a href="{{ url()->route('admin.log.export') }}?active_log={{ $active_log }}" class="btn btn-primary btn-block">Export Log</a>
				</div>
			</div>
			@endif
		</form>
	</div>
	
</div>

<div class="content-box">
	<h4 class="display-4">Mail Error Reporting Feature</h4>
	<?php
	$use_email_log = setting('log.active');
	$email_receiver = setting('log.email_receiver');
	?>
	@if($use_email_log)
		@if(!$email_receiver)
		<div class="alert alert-warning">You are still not set the email receiver for mail error reporting feature. You can set the email receiver in <a href="{{ admin_url('setting') }}" class="btn btn-sm btn-warning">Setting >> Log</a></div>
		@else
		<div class="alert alert-success">Email error reporting feature is currently active. Any error log report in this site will be sent to <strong>{{ $email_receiver }}</strong></div>
		@endif

		@if($stored_log->count() > 0)
		<div class="alert alert-info">Below are {{ $stored_log_count }} {{ $stored_log_count > $stored_log->count() ? '('.$stored_log->count().' latest data shown) ' : '' }} unreported exception in this site. You can <a href="{{ route('admin.log.mark-as-reported') }}" class="btn btn-info btn-sm">Mark All as Reported</a> if you dont want to receive the email error reporting notification for these records.</div>
		<div class="card card-body">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>URL</th>
						<th>Type</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					@foreach($stored_log as $row)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>
							<a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a>
							<div>
								<small>{{ date('d M Y H:i:s', strtotime($row->created_at)) }}</small>
							</div>
						</td>
						<td>{{ $row->type ?? 'Undefined' }}</td>
						<td>
							{{ $row->description }}
							<div>
								<a href="{{ route('admin.log.detail', ['id' => $row->id]) }}" class="btn btn-sm btn-primary btn-show-log">Detail</a>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>			
		</div>
		@else
		<div class="alert alert-success">Yeay.. currently there are no unreported error log right now. We will notify you by email if there are any error in this site.</div>
		@endif
	@else
	<div class="alert alert-warning">You are currently not using mail error reporting feature now. You can activate it in <a href="{{ admin_url('setting') }}" class="btn btn-sm btn-warning">Setting >> Log</a></div>
	@endif
</div>
@stop

@push ('modal')
<div class="modal fade fill-in" id="stacktrace-modal" tabindex="-1" role="dialog" aria-hidden="true">
	<button type="button" class="modal-custom-close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body default-modal-content">

			</div>
		</div>
	</div>
</div>
@endpush

@push ('script')
<script>
$(function(){
	$(".btn-show-log").on('click', function(e){
		e.preventDefault();
		showLoading();
		$.ajax({
			url : $(this).attr('href'),
			dataType : 'html',
			success : function(resp){
				$("#stacktrace-modal .default-modal-content").html(resp);
				$("#stacktrace-modal").modal('show');
				hideLoading();
			},
			error : function(resp){
				hideLoading();
				swal('error', ['Sorry, we cannot open the log detail right now']);
			}
		});
	});
});
</script>
@endpush