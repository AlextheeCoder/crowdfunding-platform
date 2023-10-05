<x-adminlayout>

    <h1 class="title">Admin Dashboard</h1>
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li class="divider">/</li>
				<li><a href="#" class="active">Dashboard</a></li>
			</ul>
			<div class="info-data">
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $campaignCount }}</h2>
							<p>Campaigns</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="40%"></span>
					<span class="label">40%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $userCount }}</h2>
							<p>Users</p>
						</div>
						<i class='bx bx-trending-down icon down' ></i>
					</div>
					<span class="progress" data-value="10%"></span>
					<span class="label">60%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $pledgeCount }}</h2>
							<p>Pledges</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="30%"></span>
					<span class="label">30%</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $dailyPledgeCount }}</h2>
							<p>Daily Pledges</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="80%"></span>
					<span class="label">80%</span>
				</div>
			</div>

			<div class="data">
				<div class="content-data">
					<div class="head">
						<h3>Transactions Report</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>
					<div class="chart">

						<canvas id="myChart"></canvas>
					</div>
				</div>
				<div class="content-data">
					<div class="head">
						<h3>Daily Transactions Report</h3>
						<div class="menu">
							<i class='bx bx-dots-horizontal-rounded icon'></i>
							<ul class="menu-link">
								<li><a href="#">Edit</a></li>
								<li><a href="#">Save</a></li>
								<li><a href="#">Remove</a></li>
							</ul>
						</div>
					</div>
					<div class="chart">
						<canvas id="transactionsChart"></canvas>
					</div>
				</div>
				
			
			</div>
			


			<script>
				const data = {
					labels: <?php echo json_encode($dates); ?>,
					datasets: [{
						label: 'Total Pledge Amount',
						data: <?php echo json_encode($totalPledgesPerDay); ?>,  // Use the correct variable name here
						fill: false,
						borderColor: 'rgb(75, 192, 192)',
						tension: 0.1
					}]
				};
			
				const config = {
					type: 'line',
					data: data,
					options: {
						responsive: true,
						plugins: {
							legend: {
								position: 'top',
							},
							title: {
								display: true,
								text: 'Pledge Amounts Over Time'
							}
						}
					},
				};
				var ctx = document.getElementById('myChart').getContext('2d');
				var myChart = new Chart(ctx, config);
			</script>

			<script>
				const transactionsData = {
				labels: <?php echo json_encode($dates); ?>,
				datasets: [{
					label: 'Daily Transactions (Pledges)',
					data: <?php echo json_encode($dailyTransactionCounts); ?>,
					fill: false,
					borderColor: 'rgb(255, 99, 132)',
					tension: 0.1
				}]
			};

			const transactionsConfig = {
				type: 'line',
				data: transactionsData,
				options: {
					responsive: true,
					plugins: {
						legend: {
							position: 'top',
						},
						title: {
							display: true,
							text: 'Daily Transactions (Pledges) Over Time'
						}
					}
				},
			};

			var transactionsCtx = document.getElementById('transactionsChart').getContext('2d');
			var transactionsChart = new Chart(transactionsCtx, transactionsConfig);

			</script>

</x-adminlayout>