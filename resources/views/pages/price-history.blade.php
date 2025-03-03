<div>
	<canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@^3"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

<script>
	const ctx = document.getElementById("myChart");
	new Chart(ctx, {
		type: "line",
		data: {
			datasets: [{
					label: "Diesel",
					data: @json($diesel),
					fill: false,
					borderColor: '#ff6384',
					backgroundColor: '#ff638488',
				},
				{
					label: "Gasoline",
					data: @json($gasoline),
					fill: false,
					borderColor: '#4bc0c0',
					backgroundColor: '#4bc0c088',
				},
			]
		},
		options: {
			animation: false,
			lineTension: 0,
			pointRadius: 0,
			stepped: true,
			scales: {
				x: {
					type: 'time',
					time: {
						unit: 'month',
					},
				},
				y: {
					min: 0,
				},
			},
		},
	});
</script>
