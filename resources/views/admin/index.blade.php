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
						@if($todaycampaigncount > 0)
						<i class='bx bx-trending-up icon' ></i>
						@else
    					<i class='bx bx-trending-down icon down' ></i>
					@endif
					</div>
					<span class="progress" data-value="{{$campaignpercent}}%"></span>
					<span class="label">{{$campaignpercent}}%  From yesterday</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $userCount }}</h2>
							<p>Users</p>
						</div>
						@if($todayUsersCount > 0)
						<i class='bx bx-trending-up icon' ></i>
						@else
    					<i class='bx bx-trending-down icon down' ></i>
					@endif
					</div>
					<span class="progress" data-value="{{$userpercent}}%"></span>
					<span class="label">{{$userpercent}}%  From yesterday</span>
					

				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $pledgeCount }}</h2>
							<p>Pledges</p>
						</div>
						@if($todaypledgecount > 0)
						<i class='bx bx-trending-up icon' ></i>
						@else
    					<i class='bx bx-trending-down icon down' ></i>
					@endif
					</div>
					<span class="progress" data-value="{{$pledgepercent}}%"></span>
					<span class="label">{{$pledgepercent}}%  From yesterday</span>
				</div>
				<div class="card">
					<div class="head">
						<div>
							<h2>{{ $dailyPledgeCount }}</h2>
							<p>Daily Pledges</p>
						</div>
						<i class='bx bx-trending-up icon' ></i>
					</div>
					<span class="progress" data-value="{{$pledgepercent}}%"></span>
					<span class="label">{{$pledgepercent}}%  From yesterday</span>
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

			<div class="members-container">
				<div class="members-header">
					<h3>Latest Members</h3>
					<div class="header-actions">
						<span class="new-members">{{ $todayUsersCount }} New Members</span>
						<button class="close-btn">✕</button>
					</div>
				</div>
				<div class="members-grid">
					@foreach($latestUsers as $user)
						<div class="member">
							<img src="{{$user->profile ? asset('storage/' . $user->profile) : asset('/images/homies.jpg')}}"  alt="">
							<p>{{ $user->firstname }}</p>
							<span>{{ $user->created_at->diffForHumans() }}</span> <!-- This will display "3 days ago", "1 month ago", etc. -->
						</div>
					@endforeach
				</div>				
				<div class="view-all">
					<a href="#">View All Users</a>
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