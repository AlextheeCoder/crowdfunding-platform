<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crowdfunding</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/scss/admin.scss','resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <div class="logo"> <a href="/">Alenderx</a>
      </div>
      <ul class="menu">
        <li><a href="/discover">Discover</a></li>
        @auth
 
        @if (!auth()->user()->ethereum_address)
        <div class="logout">
          <form style="display:inline" method="POST" action="{{ route('user.storeAddress') }}" id="connect-wallet-form">
            @csrf
            <button type="submit" style="text-decoration: none; color:black; margin-right:20px;">
              Connect Wallet
            </button>
          </form>
        </div> 
        @endif

               
        <li><a href="/create">Start a project</a></li>
        <li>
          <a href="/message" class="notification">
            <span><i class="fa fa-envelope"></i></span>
            <span class="badge" id="unread-count">0</span>
          </a>
        </li>
        <div class="dropdown">
          <li class="dropbtn"><i class="fa fa-bars"></i> 
            {{auth()->user()->name}} 
          </li>
          <div class="dropdown-content">
            <a href="/profile">Profile</a>
            <a href="/message">Messages: <span id="unread-count" style="color: red">0</span></a>
            <form style="display:inline" method="POST" action="/logout" id="logout-form">
              @csrf
              <button type="submit" style="text-decoration: none; color:black; margin-right:20px;" >
                <i class="fa fa-sign-out"></i> Logout
              </button>
            </form>
          </div>
        </div>

        

        @else
        <li><a href="/login">Sign in</a></li>
        @endauth
       
      </ul>
    </nav>
  </header>

  <main>
    {{$slot}}

    
  </main>
  
  <footer>
    <p>&copy; 2023 Alenderx by Alex Mwai Muthee. All rights reserved.</p>
  </footer>
  <x-flash-message />
</body>
<!-- Add this script tag to your Blade file or include your separate JavaScript file -->

<script src="https://cdn.jsdelivr.net/npm/web3@1.5.2/dist/web3.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/metamask.js') }}"></script>
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

</html>
