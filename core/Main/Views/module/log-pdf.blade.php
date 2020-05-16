<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Site Exception Report Detail</title>
<style>
	html, body{
		font-family:sans-serif;
		font-size:12px;
	}

	table th{
		background:#333333;
		color:#ffffff;
		padding:.5em;
	}

	table th a{
		color:#ffffff;
	}
	table td{
		border:1px solid #333;
	}
	p.lead{
		font-size:20px;
		text-align:center;
		margin:.5em 0;
	}
	p.main{
		font-weight:bold;
		font-size:15px;
	}
</style>
</head>
<body>
	<h1>Site Exception Error Detail</h1>
	<p>Site : <a href="{{ url('/') }}">{{ url('/') }}</a></p>
	<p>Date Reported : {{ date('d F Y H:i:s') }}</p>


	@foreach($data as $row)
	<table style="width:100%; margin-bottom:20px;">
		<thead>
			<tr>
				<th width=100>#{{ $loop->iteration }}</th>
				<th>Type : {{ $row->type ?? 'UNDEFINED' }}</th>
			</tr>
			<tr>
				<td colspan=2><a href="{{ $row->url }}" target="_blank">{{ $row->url }}</a></td>
			</tr>
			<tr>
				<td colspan=2>
					<p class="lead">{{ strlen($row->description) > 0 ? $row->description : 'No Message' }}</p>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<p class="main">{{ $row->file_path }}</p>
					<?php
					$stacktrace = json_decode($row->backtrace, true);
					?>
					<ul>
						@if($stacktrace)
							@foreach($stacktrace as $s)
							<li class="list-group-item">
								{{ isset($s['file']) ? $s['file'] : '-'}}
								<br>
								@if(isset($s['class']))
								<strong>{{ $s['class'] }}</strong>
								@endif
								@if(isset($s['function']))
								method <strong>{{ $s['function'] }}</strong>
								@endif
								@if(isset($s['line']))
									Line {{ $s['line'] }}
								@endif
							</li>
							@if($loop->iteration > 20)
								<li>
									... 
								</li>
								@break
							@endif
							@endforeach
						@endif
					</ul>
				</td>
			</tr>
		</thead>
	</table>
	@endforeach
</body>
</html>