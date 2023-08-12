<x-layout>
    <div class="user-info">
        <div class="profile-section">
            <img src="{{$campaign->user->profile ? asset('storage/' . $campaign->user->profile) : asset('/images/homies.jpg')}}"  alt="">
            <h4 class="stats">Name:<span>{{$campaign->user->name}}</span></h4>
            <h4  class="stats">Email: <span>{{$campaign->user->email}}</span></h4>
            <h4  class="stats">Date joined: <span>{{$campaign->user->created_at}}</span></h4>
            <h4  class="stats">User rating:<span>5</span></h4>
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
    
</x-layout>