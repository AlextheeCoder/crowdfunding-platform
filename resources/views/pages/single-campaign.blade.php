<x-layout>
    <div class="single">
      <h1>{{$campaign->title}}</h1>
      <div class="toppart">
        <div class="imagesection">
          <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}" alt="">
        </div>
        <div class="pledgesection">
          
          @auth
              <a class="stats" href="/user/{{$campaign->id}}">Creator</a>
              <span>{{ $campaign->user->firstname }}</span>
          @else
              <a class="stats" href="#">Creator</a>
              <span>{{ $campaign->user->firstname }}</span>
          @endauth
      
          <h4 class="stats">Investors</h4>
          <span>{{ $investorsCount }}</span>
      
          <h4 class="stats">Target in Eth</h4>
          <span>{{$campaign->target}}</span>
          <h4 class="stats">Amount Raised in Eth</h4>
          <span>{{$amountRaised}}</span>
      
          @auth
              @if (auth()->user()->ethereum_address)
                  <form method="POST" action="{{ route('campaign.pledge', ['id' => $campaign->id]) }}" id="pledge-form">
                      @csrf
                      <div class="part">
                          <label for="pledge">Amount in ETH</label>
                          <input type="number" id="pledge" name="pledge" step="0.01" required>
                      </div>
                      <div class="part">
                          <button type="submit" id="pledge-button">Pledge</button>
                      </div>
                  </form>
              @endif
          @endauth
      </div>
      
      </div>
      <div class="description-container">
      
        <div class="description" >
          <h3>About This  Campaign</h3>
          <p> {!! $campaign->description !!}</p>
        </div>
        <div class="investment-details">
          <h3>Investment Details</h3>
          <div class="details-content">
              <p><strong>Offering Type:</strong> {{ $campaign->offering_type }}</p>
              
              @if($campaign->offering_type == 'equity')
                  <p><strong>Asset Type:</strong> {{ $campaign->asset_type }}</p>
                  <p><strong>Price Per Share:</strong> {{ $campaign->price_per_share }}</p>
                  <p><strong>Valuation:</strong> {{ $campaign->valuation }}</p>
                  <p><strong>Min Investment:</strong> {{ $campaign->min_investment }}</p>
              @elseif($campaign->offering_type == 'product_crowdfunding')
                  <p><strong>Asset Type:</strong> {{ $campaign->asset_type }}</p>
              @endif
          </div>
      </div>
     
    
      
      </div>
      <div class="comment-section">
        <h2>Comments</h2>
    
        @auth
    @if($hasBacked || $isCreator)
        <form action="{{ route('comments.store', ['campaign' => $campaign->id]) }}" method="post">
            @csrf
            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
            <textarea name="body" required></textarea>
            <button type="submit">Add Comment</button>
        </form>
    @else
        <p>You need to back this campaign first to comment.</p>
    @endif
@else
    <p>You need to <a href="{{ route('login') }}">login</a> to comment.</p>
@endauth

    
    
    <div class="comment-container">
      @foreach($comments as $comment)
      <div class="comment">
          <strong><img src="{{$comment->user->profile ? asset('storage/' . $comment->user->profile) : asset('/images/homies.jpg')}}"  alt=""> {{ $comment->user->firstname }} {{ $comment->user->sirname }}</strong>
          <span>{{ $comment->created_at->diffForHumans() }}</span>
          <p>{{ $comment->body }}</p>
          
          @auth
                @if($hasBacked || $isCreator)
          <button class="toggle-reply-form">Reply</button>
          @else
       
    @endif
@else
    
@endauth
          <div class="reply-form" style="display: none;">
            <form action="{{ route('comments.store', ['campaign' => $campaign->id]) }}" method="post">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <textarea name="body" required></textarea>
                <button type="submit">Reply</button>
            </form>
        </div>
<!-- Display replies -->
        <div class="replies">
          @foreach($comment->replies as $reply)
              <div class="reply">
                <strong><img src="{{$reply->user->profile ? asset('storage/' . $reply->user->profile) : asset('/images/homies.jpg')}}"  alt=""> {{ $reply->user->firstname }} {{ $reply->user->sirname }} replied:</strong>
                <span>{{ $reply->created_at->diffForHumans() }}</span>
                  <p>{{ $reply->body }}</p>
              </div>
          @endforeach
          
      </div>
        
          <!-- Reply button and form goes here... -->
      </div>
  @endforeach
  
    </div>
       
        
    

    </div>
      
      <div class="overlay" id="overlay" style="display: none"></div>
      <div class="loading-spinner" id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
        <div class="spinner-text" style="margin-top: 10px">Processing Transaction...</div>
      </div>
    </div>
  
    <script src="https://cdn.jsdelivr.net/npm/web3@1.5.3/dist/web3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
    
    </script>
    <script>
      (async function () {
        // Check if MetaMask is installed and enable Ethereum provider
        if (typeof window.ethereum !== 'undefined') {
          await window.ethereum.enable();
        }
  
        // Create a Web3 instance
        const web3 = new Web3(window.ethereum);
  
        // Get the campaign owner's Ethereum address from the server
        const campaignOwnerAddress = "{{$campaign->ethereum_address}}";
  
        // Get the current user's Ethereum account address
        const accounts = await web3.eth.getAccounts();
        const userAddress = accounts[0];
  
        // Get the pledge form and submit button
        const pledgeForm = document.getElementById('pledge-form');
        const pledgeButton = document.getElementById('pledge-button');

        const loadingSpinner = document.getElementById('loading-spinner');
       const overlay=document.getElementById('overlay');
  
        // Add event listener to the pledge form submission
        pledgeForm.addEventListener('submit', async function (event) {
          event.preventDefault(); // Prevent form submission
  
          loadingSpinner.style.display = 'block';
          overlay.style.display='block';
          // Disable the pledge button to prevent multiple pledges
          pledgeButton.disabled = true;
  
          // Get the pledge amount from the form
          const pledgeAmount = document.getElementById('pledge').value;
  
          // Prepare the transaction parameters
          const transactionParameters = {
            from: userAddress,
            to: campaignOwnerAddress,
            value: web3.utils.toWei(pledgeAmount, 'ether'),
          };
  
          // Send the transaction
          try {
            const receipt = await web3.eth.sendTransaction(transactionParameters);
  
            // Transaction successful
            alert('Pledge successful!'); // Display success message or redirect
  
            // Submit the pledge form to the server for storage
            const route = "{{ route('campaign.pledge', ['id' => $campaign->id]) }}";
            const formData = new FormData(pledgeForm);
            const response = await axios.post(route, formData, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            });
  
            // Check if the response was successful
            if (response.status === 200) {
              // Redirect to the specified URL
              window.location.href = '/'; // Replace with the desired URL
            } else {
              console.log(response.data); // Optional: Log the server response
              alert('Pledge created successfully, but redirection failed.'); // Display a message indicating redirection failure
            }
          } catch (error) {
            // Transaction failed
            console.error(error);
            alert('Pledge failed. Please try again.'); // Display error message
          }
          loadingSpinner.style.display = 'none';
          
          // Enable the pledge button again
          pledgeButton.disabled = false;
        });
      })();
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
    // Get all the "Reply" buttons
    let replyButtons = document.querySelectorAll('.toggle-reply-form');

    // Add click event to each "Reply" button
    replyButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Find the closest reply form to the clicked button
            let replyForm = this.nextElementSibling;
            // Toggle the display of the reply form
            if (replyForm.style.display === "none") {
                replyForm.style.display = "block";
            } else {
                replyForm.style.display = "none";
            }
        });
    });
});

    </script>
  </x-layout>
  