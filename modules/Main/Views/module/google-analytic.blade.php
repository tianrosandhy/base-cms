@if(isset($analytic->report))
	<script src="{{ admin_asset('vendor/chart.js/Chart.min.js') }}"></script>
	<form action="" class="dashboard-form my-4">
		<div class="card card-body">
			<div class="row">
				<div class="col-sm-8">
					<div class="form-group custom-form-group">
						<label>Date Filter</label>
						@include ('main::inc.daterange-helper', [
							'attr' => 'class="form-control" name="period[]"',
							'oldVal' => old('period', request()->period)
						])
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group custom-form-group">
						<label>Max Data per Table Report</label>
						<input type="number" name="max_result" class="form-control" min=5 max=50 placeholder="Default : 10" value="{{ old('max_result', request()->max_result) }}">
					</div>					
				</div>
			</div>
			<button class="btn btn-primary btn-block" type="submit"><i class="fa fa-filter"></i> Filter Dashboard</button>

			@if(request()->period)
			<a href="{{ admin_url('/') }}" class="btn btn-block btn-danger"><i class="fa fa-times-circle-o"></i> Reset Filter</a>
			@endif
		</div>
	</form>

	@if(!request()->max_result && !request()->period)
	<div class="alert alert-info mb-5">
		Below are current month report. To generate by specific period of time, please change the date filter above.
	</div>
	@endif


	@if(isset($analytic->report['weekly']))
	<div class="card">
		<div class="card-header">
			This Week Report
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-sm-6">
					<canvas id="analytic-visitor-weekly" width="100%" height=100></canvas>
				</div>
				<div class="col-sm-6">
					<canvas id="analytic-pageview-weekly" width="100%" height=100></canvas>
				</div>
			</div>
		</div>
	</div>

	@include ('main::module.dashboard.chart-script', [
		'type' => 'line',
		'title' => 'Weekly Visitor Report',
		'canvas_id' => 'analytic-visitor-weekly',
		'label' => $analytic->report['weekly']['label'],
		'dataset' => [
			[
				'label' => 'Last Week',
				'borderColor' => '#D8C733',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['weekly']['last_week']['visitor']
			],
			[
				'label' => 'Current Week',
				'borderColor' => '#1FBBE8',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['weekly']['this_week']['visitor']
			],
		]
	])

	@include ('main::module.dashboard.chart-script', [
		'type' => 'line',
		'title' => 'Weekly Pageview Report',
		'canvas_id' => 'analytic-pageview-weekly',
		'label' => $analytic->report['weekly']['label'],
		'dataset' => [
			[
				'label' => 'Last Week',
				'borderColor' => '#D8C733',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['weekly']['last_week']['pageview']
			],
			[
				'label' => 'Current Week',
				'borderColor' => '#1FBBE8',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['weekly']['this_week']['pageview']
			],
		]
	])
	@endif



	@if(isset($analytic->report['monthly']))
	<div class="card mt-3">
		<div class="card-header">
			This Month Visitor & Pageview Report
		</div>
		<div class="card-body">
			<canvas id="analytic-monthly" width="100%" height=50></canvas>
		</div>
	</div>

	@include ('main::module.dashboard.chart-script', [
		'type' => 'line',
		'title' => 'Monthly Report',
		'canvas_id' => 'analytic-monthly',
		'label' => $analytic->report['monthly']['label'],
		'dataset' => [
			[
				'label' => 'Visitor',
				'borderColor' => '#7E8BE8',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['monthly']['visitor']
			],
			[
				'label' => 'Page View',
				'borderColor' => '#3744A1',
				'backgroundColor' => 'transparent',
				'data' => $analytic->report['monthly']['pageview']
			],
		]
	])
	@endif




	@if(isset($analytic->report['ranged']))
	<div class="card mt-3">
		<div class="card-header">
			Report by Filtered Date {{ $period_string }}
		</div>
		<div class="card-body">
			@if($datediff < 10)
			<div class="row">
				<div class="col-sm-6">
			@endif

			<canvas class="mb-5" id="analytic-ranged-pageview" width="100%" height=50></canvas>

			@if($datediff < 10)
				</div>
				<div class="col-sm-6">
			@endif

			<canvas id="analytic-ranged-visitor" width="100%" height=50></canvas>

			@if($datediff < 10)
				</div>
			</div>
			@endif

		</div>
	</div>

	@include ('main::module.dashboard.chart-script', [
		'type' => 'line',
		'title' => 'Filtered Date Pageview Report',
		'canvas_id' => 'analytic-ranged-pageview',
		'label' => $analytic->report['ranged']['label'],
		'dataset' => [
			[
				'label' => 'Page View',
				'borderColor' => '#7E8BE8',
				'data' => $analytic->report['ranged']['pageview']
			],
		]
	])
	@include ('main::module.dashboard.chart-script', [
		'type' => 'line',
		'title' => 'Filtered Date Visitors Report',
		'canvas_id' => 'analytic-ranged-visitor',
		'label' => $analytic->report['ranged']['label'],
		'dataset' => [
			[
				'label' => 'Visitors',
				'borderColor' => '#3744A1',
				'data' => $analytic->report['ranged']['visitor']
			],
		]
	])
	@endif



	<div class="row">
	@if(isset($analytic->report['most_visited']))
		<div class="col-sm-6">
			<div class="card mt-3">
				<div class="card-header">Most Visited Pages {{ $period_string }}</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>No</th>
								<th>Title</th>
								<th>Pageviews</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total_pageview = 0;
							?>
							@foreach($analytic->report['most_visited'] as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									<a target="_blank" href="{{ url($row['url']) }}">{{ $row['pageTitle'] }}</a>
								</td>
								<td align="right">{{ number_format($row['pageViews']) }}</td>
								<?php
								$total_pageview += $row['pageViews'];
								?>
							</tr>
							@endforeach
						</tbody>
						@if($total_pageview > 0)
						<tfoot>
							<tr>
								<td></td>
								<td></td>
								<td align="right"><strong>{{ number_format($total_pageview) }}</strong></td>
							</tr>
						</tfoot>
						@endif
					</table>
				</div>
			</div>
		</div>
	@endif

	@if(isset($analytic->report['top_landing']))
		<div class="col-sm-6">
			<div class="card mt-3">
				<div class="card-header">Top Landing Page {{ $period_string }}</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>No</th>
								<th>Title</th>
								<th><i class="fa fa-sign-in" title="Entrance"></i></th>
								<th><i class="fa fa-sign-out" title="Bounce"></i></th>
								<th><i class="fa fa-percent" title="Bounce Rate"></i></th>
							</tr>
						</thead>
						<tbody>
							@foreach($analytic->report['top_landing'] as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									<a target="_blank" href="{{ url($row['url']) }}">{{ $row['title'] }}</a>
								</td>
								<td align="right">{{ number_format($row['entrances']) }}</td>
								<td align="right">{{ number_format($row['bounces']) }}</td>
								<td align="right">{{ number_format($row['bounce_rate']) }}%</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	@endif
	</div>

	<div class="row">
		@if(isset($analytic->report['session_duration']))
		<div class="col-sm-6">
			<div class="card mt-3">
				<div class="card-header">Total Page Session Duration Report {{ $period_string }}</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>No</th>
								<th>Title</th>
								<th>Duration</th>
								<th>Avg. Duration</th>
							</tr>
						</thead>
						<tbody>
							@foreach($analytic->report['session_duration'] as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>
									<a target="_blank" href="{{ url($row['url']) }}">{{ $row['title'] }}</a>
								</td>
								<td align="right">
									{{ $row['session_duration'] }}
									<div>
										<span class="badge badge-info">{{ number_format($row['session_count']) }} sessions</span>
									</div>
								</td>
								<td align="right">{{ $row['avg_session_duration'] }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@endif

		@if(isset($analytic->report['device']))
		<div class="col-sm-6">
			<div class="card mt-3">
				<div class="card-header">Users Browser & Device Report {{ $period_string }}</div>
				<div class="card-body">
					<table class="table">
						<thead>
							<tr>
								<th>No</th>
								<th>Browser</th>
								<th>OS</th>
								<th>Users</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total_user = 0;
							?>
							@foreach($analytic->report['device'] as $row)
							<tr>
								<td>{{ $loop->iteration }}</td>
								<td>{{ $row['browser'] }}</td>
								<td>{{ $row['os'] }}</td>
								<td align="right">{{ number_format($row['count']) }}</td>
							</tr>
							<?php
							$total_user += $row['count'];
							?>
							@endforeach
						</tbody>
						@if($total_user > 0)
						<tfoot>
							<tr>
								<td colspan=3 align="right"></td>
								<td align="right">
									<strong>{{ number_format($total_user) }}</strong>
								</td>
							</tr>
						</tfoot>
						@endif
					</table>
				</div>
			</div>
		</div>
		@endif
	</div>
@else
<div class="card card-body">
	<p class="lead">To use the Google Analytic Dashboard, please provide the Google Service Account Credentials & Google Analytic View ID.</p>
</div>
@endif