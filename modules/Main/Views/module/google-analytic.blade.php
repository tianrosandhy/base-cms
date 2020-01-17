@if(isset($analytic->report))
	<script src="{{ admin_asset('vendor/chart.js/Chart.min.js') }}"></script>

	@if(isset($analytic->report['weekly']))
	<div class="card">
		<div class="card-header">
			Weekly Report
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
		'type' => 'bar',
		'title' => 'Weekly Visitor Report',
		'canvas_id' => 'analytic-visitor-weekly',
		'label' => $analytic->report['weekly']['label'],
		'dataset' => [
			[
				'label' => 'Last Week',
				'backgroundColor' => '#D8C733',
				'data' => $analytic->report['weekly']['last_week']['visitor']
			],
			[
				'label' => 'Current Week',
				'backgroundColor' => '#1FBBE8',
				'data' => $analytic->report['weekly']['this_week']['visitor']
			],
		]
	])

	@include ('main::module.dashboard.chart-script', [
		'type' => 'bar',
		'title' => 'Weekly Pageview Report',
		'canvas_id' => 'analytic-pageview-weekly',
		'label' => $analytic->report['weekly']['label'],
		'dataset' => [
			[
				'label' => 'Last Week',
				'backgroundColor' => '#D8C733',
				'data' => $analytic->report['weekly']['last_week']['pageview']
			],
			[
				'label' => 'Current Week',
				'backgroundColor' => '#1FBBE8',
				'data' => $analytic->report['weekly']['this_week']['pageview']
			],
		]
	])
	@endif
@else
<div class="card card-body">
	<p class="lead">To use the Google Analytic Dashboard, please provide the Google Service Account Credentials & Google Analytic View ID.</p>
</div>
@endif