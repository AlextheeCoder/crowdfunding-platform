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
                <p><strong>Address</strong>: E104, catn-2, Chandlodia Ahmedabad Gujarat, UK.</p>
                <div class="social-stats">
                    <div class="facebook">Delete</div>
                    <div class="twitter">Message</div>
                    <div class="google-plus">Warn</div>
                </div>
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
                   <p>No comments yet...</p>
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
                                    <th>Title</th>
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
</x-adminlayout>