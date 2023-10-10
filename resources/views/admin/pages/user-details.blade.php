<x-adminlayout>
    <div class="user-details">
        <div class="profile-card">
            <img src="{{$user->profile ? asset('storage/' . $user->profile) : asset('/images/homies.jpg')}}"  alt="">
            <div class="profile-info">
                <div class="name-designation">
                    <h2>{{ $user->firstname }} {{ $user->sirname }}</h2>
                    <p>{{ $user->role }}</p>
                </div>
                <div class="email-phone">
                    <p><strong>Email ID</strong>: {{ $user->email}}</p>
                    <p><strong>Gender</strong>: {{ $user->gender}}</p>
                </div>
                @if (!$user->ethereum_address)
                <p><strong>Ethereum Address</strong>: Not set</p>  
                @else
                <p><strong>Ethereum Address</strong>: {{ $user->ethereum_address}}</p>
                @endif
                <div class="social-stats">
                    <div class="delete">
                        <form method="POST" action="{{ route('user.delete', ['user' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button><i class="fa fa-trash" aria-hidden="true"></i>  Delete</button>
                      </form>
                    </div>
                     @if ($user->id == auth()->user()->id)

                     @else
                    <div class="message" id="toggle-message-form">Message</div>
                   
                    
                    @endif
                </div>
            </div>
           
            <div class="message-box" style="display: none;">
                <form action="" method="POST">
                    @csrf
            
                    <!-- User Input for Message -->
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="form-group">
                        <label for="message">Your Message:</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
            
                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit">Send Message</button>
                    </div>
                </form>
            </div>
            
        </div>    
        <div class="card-container">
            <ul class="tabs">
                <li class="active-tab"><a href="#activity2">Activity</a></li>
                <li><a href="#campaigns">Campaigns</a></li>
                <li><a href="#details2">Pledges</a></li>
            </ul>
        
            <div class="tab-content">
                <div id="activity2" class="content-panel visible">
                    @if(count($user->comments) > 0)
                        @foreach($user->comments as $comment)
                            <div class="comment">
                                <strong>{{$comment->user->name}} Commented on: <span>{{ $comment->campaign->user->firstname }} {{ $comment->campaign->user->sirname }}'s campaign</span> </strong>
                                <p>{{ $comment->body }}</p>
                            </div>
                        @endforeach
                    @else
                        <p>No comments yet...</p>
                    @endif
                </div>
                
                <div id="campaigns" class="content-panel hidden">
                    <div class="camaign-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Target</th>
                                    <th>Status</th>
                                    <th>Deadline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                @foreach ($user->campaigns as $campaign)
                                <tr>
                                    <td>#{{ $campaign->id}}</td>
                                    <td> <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}" alt=""> {{ $campaign->title }}</td>
                                    <td>{{ $campaign->target }} ETH</td>
                                    <td><span class="status pending">Pending</span></td>
                                    <td>{{ $campaign->deadline}}</td>
                                    <td><a href="/discover/{{$campaign->id}}" class="details-btn">View</a></td>
                                </tr>
                                @endforeach
                                <!-- Additional rows can be added similarly -->
                            </tbody>
                        </table>
                       
                    </div>
                    
                </div>
                <div id="details2" class="content-panel hidden">
                    <div class="camaign-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Receiver</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                @foreach($user->pledges as $pledge)
                                <tr>
                                    <td>#{{ $pledge->id}}</td>
                                    <td> <img src="{{$pledge->campaign->image ? asset('storage/' . $pledge->campaign->image) : asset('/images/homies.jpg')}}" alt="">{{ $pledge->campaign->user->firstname }} {{ $pledge->campaign->user->sirname }}</td>
                                    <td>{{ $pledge->amount }} ETH</td>
                                    <td>{{ $pledge->campaign->created_at->diffForHumans() }}</td>
                                    
                                </tr>
                                <!-- Additional rows can be added similarly -->
                                @endforeach
                            </tbody>
                        </table>
                       
                    </div>
                </div>
            </div>
        </div>
        
        
    </div> 

    <script>
        document.addEventListener("DOMContentLoaded", function() {
    let tabs = document.querySelectorAll(".tabs a");
    tabs.forEach(function(tab) {
        tab.addEventListener("click", function(e) {
            e.preventDefault();
            
            // Remove active state from all tabs
            tabs.forEach(function(innerTab) {
                innerTab.parentElement.classList.remove("active-tab");
            });

            // Set current tab as active
            tab.parentElement.classList.add("active-tab");

            // Hide all tab content panels
            let panels = document.querySelectorAll(".content-panel");
            panels.forEach(function(panel) {
                panel.classList.remove("visible"); // Hide panels by removing "visible" class
            });

            // Show the clicked tab's content panel
            let targetPanel = document.querySelector(tab.getAttribute("href"));
            targetPanel.classList.add("visible"); // Display the panel by adding "visible" class
        });
    });
});

     </script>
     <script>
        // Get the toggle button and the form
let toggleButton = document.getElementById('toggle-message-form');
let messageBox = document.querySelector('.message-box');

// Add a click event listener to the toggle button
toggleButton.addEventListener('click', function() {
    // Check if the message box is currently hidden
    if (messageBox.style.display === "none" || messageBox.style.display === "") {
        // If it's hidden, show it
        messageBox.style.display = "block";
    } else {
        // If it's visible, hide it
        messageBox.style.display = "none";
    }
});

     </script>

     <script>
        document.addEventListener('DOMContentLoaded', function () {
    const messageForm = document.querySelector('.message-box form');

    messageForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Get the form data
        const formData = new FormData(messageForm);

        // Send the message using AJAX
        fetch('{{ route('sendMessageToUser') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}', // Add CSRF token in the header
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully!');
                // You can redirect or simply reset the form
                messageForm.reset();
            } else {
                alert('Failed to send message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
    });
});

     </script>

</x-adminlayout>