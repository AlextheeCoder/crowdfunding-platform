<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	@vite(['resources/css/app.css','resources/scss/admin.scss','resources/js/app.js'])
	<title>AdminSite</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
	
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="/admin" class="brand"><i class='bx bxs-smile icon'></i> Alenderx</a>
		<ul class="side-menu">
			<li><a href="/admin" class="active"><i class='bx bxs-dashboard icon' ></i>Dashboard</a></li>
			<li class="divider" data-text="main">Main</li>
			<li><a href="/users/manage"><i class='bx bxs-user icon' ></i> User Managment</a></li>
			<li><a href="/campaign/manage"><i class='bx bxs-book icon' ></i> Campaign Managment</a></li>
			<li><a href="/transactions/manage"><i class='bx bx-transfer icon' ></i>Transactions</a></li>
			<li><a href="/analytics"><i class='bx bx-pie-chart-alt-2 icon' ></i>Analytics</a></li> 
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- NAVBAR -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu toggle-sidebar' ></i>
			<form action="#">
				<div class="form-group">
					<input type="text" placeholder="Search...">
					<i class='bx bx-search icon' ></i>
				</div>
			</form>
			<a href="/reports" class="nav-link">
				<i class='bx bxs-bell icon' ></i>
				<span class="badge" id="unread-reports">0</span>
			</a>
			<a href="/message/admin" class="nav-link">
				<i class='bx bxs-message-square-dots icon' ></i>
				<span class="badge"  id="unread-count">0</span>
			</a>
			<span class="divider"></span>
			<div class="profile">
				<img src="{{auth()->user()->profile ? asset('storage/' . auth()->user()->profile) : asset('/images/homies.jpg')}}"  alt="">
				<ul class="profile-link">
					<li><a href="#"><i class='bx bxs-user-circle icon' ></i> Profile</a></li>
					<li><a href="#"><i class='bx bxs-cog' ></i> Settings</a></li>
					<li>
						<form action="/admin/logout" method="POST">
							@csrf
							<button class="logout-btn" type="submit"><i class='bx bxs-log-out-circle' style="margin-left: 20px;"></i> Logout</button>
						</form>
					</li>
				</ul>
			</div>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			{{$slot}}
		</main>
		<!-- MAIN -->
	</section>
	<!-- NAVBAR -->

</body>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="{{ asset('js/admin.js') }}"></script>
@auth
<script>
function updateUnreadMessageCount() {
const unreadCountElement = document.getElementById('unread-count');

fetch('/unread-messages-count')
  .then(response => response.json())
  .then(data => {
	const unreadCount = data.unread_count;
	unreadCountElement.textContent = unreadCount;
  })
  .catch(error => {
	console.error('Error fetching unread message count:', error);
  });
}

// Call the function initially to show the unread count on page load
updateUnreadMessageCount();

// Periodically update the unread message count every 10 seconds
setInterval(updateUnreadMessageCount, 10000); // 10000 milliseconds = 10 seconds
</script>
@endauth
</html>