<x-layout>
    <div class="user-info">
        <div class="profile-section">
            <img src="{{$campaign->user->profile ? asset('storage/' . $campaign->user->profile) : asset('/images/homies.jpg')}}"  alt="">
            <h4 class="stats">First Name:<span>{{$campaign->user->firstname}}</span></h4>
            <h4 class="stats">Sirname:<span>{{$campaign->user->sirname}}</span></h4>
            <h4  class="stats">Email: <span>{{$campaign->user->email}}</span></h4>
            <h4  class="stats">Date joined: <span>{{$campaign->user->created_at->diffForHumans()}}</span></h4>
            
        </div>

        <div class="campaign-info">
            <h4>Current Campaigns</h4>
          <ul class="responsive-list">
            @foreach ($campaign->user->campaigns as $campaign)
            <li class="list-header"> 
                <div class="camp"> <a href="/discover/{{$campaign->id}}">{{ $campaign->title }}</a></div>
            </li>
            @endforeach
          </ul>

        </div>
        

        <div class="chat">
            @if ($campaign->user->id == auth()->user()->id)
            
            <a href="/profile">Visit Your Pofile</a>
        @else
        
        <h3>Send Message</h3>
        <form action="{{ route('sendMessageToCreator') }}" method="POST" id="message-form">
            @csrf
            <div class="part">
                <input type="hidden" name="campaign_id" value="{{ $campaign->user->id }}">
            </div>
            <div class="part">
                <textarea name="message" id="message" cols="30" rows="3" placeholder="Type your message..."></textarea>
            </div>
            <div class="part">
                <button type="submit">Send Message</button>
            </div> 
        </form>
        @endif

      
        
        </div>
        <button class="report-btn" id="reportUserButton">Report User</button>

        <div class="report-box" style="display: none;">
            
            <form action="" method="POST">
                @csrf
                <h5>Reason for reporting:  </h5>
                <!-- User Input for Message -->
                <input type="hidden" name="user_id" value="{{$campaign->user->id}}">
                <div class="part">
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
        
                <!-- Submit Button -->
                <div class="part">
                    <button type="submit">Report</button>
                </div>
            </form>
        </div>
    </div>

    <script>
                document.addEventListener('DOMContentLoaded', function () {
            const messageForm = document.getElementById('message-form');

            messageForm.addEventListener('submit', function (event) {
                event.preventDefault();

                // Get the form data
                const formData = new FormData(messageForm);

                // Send the message using AJAX
                fetch('{{ route('sendMessageToCreator') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}', // Add CSRF token in the header
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response if needed
                    if (data.success) {
                        // Optionally, display a success message to the user
                        alert('Message sent successfully!');
                        window.location.href = '/message';
                    } else {
                        // Handle any errors that may occur
                        alert('Failed to send message. Please try again.');
                    }
                })
                .catch(error => {
                    // Handle any network errors or other issues
                    console.error('Error:', error);
                    alert('An error occurred. Please try again later.');
                });
            });
        });

    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    let reportButton = document.getElementById('reportUserButton');
    let reportBox = document.querySelector('.report-box');

    reportButton.addEventListener('click', function() {
        if (reportBox.style.display === "none" || reportBox.style.display === "") {
            reportBox.style.display = "block";
        } else {
            reportBox.style.display = "none";
        }
    });
});
    </script>
</x-layout>