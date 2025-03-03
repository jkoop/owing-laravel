<div>
	<canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js">
</script>

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
			plugins: {
				annotation: {
					annotations: {
						start: {
							xMin: "2022-12-11T16:00:00Z",
							xMax: "2022-12-11T16:00:00Z",
							label: {
								content: "Automated Prices",
								display: true,
								position: "end",
							},
						},
						owing: {
							xMin: "2024-01-01T16:00:00Z",
							xMax: "2024-01-01T16:00:00Z",
							label: {
								content: "Migrate to Owing",
								display: true,
								position: "end",
							},
						},
					},
				},
			},
		},
	});
</script>
