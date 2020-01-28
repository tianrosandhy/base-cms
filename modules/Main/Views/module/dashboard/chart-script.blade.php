<script>
	var barChartData = {
		labels: {!! json_encode($label) !!},
		datasets: [
			@foreach($dataset as $rd)
			{!! json_encode($rd) !!},
			@endforeach
		]
	};

	var ctx = document.getElementById('{{ $canvas_id }}').getContext('2d');
	window.myBar = new Chart(ctx, {
		type: '{{ $type }}',
		data: barChartData,
		options: {
			title: {
				display: true,
				text: '{{ $title }}'
			},
			tooltips: {
				mode: 'index',
				intersect: false
			},
			responsive: true,
			scales : {
				yAxes : [{
					ticks : {
						beginAtZero : true
					}
				}]
			}
		}
	});
</script>