<x-layout>
   <div class="profile">
    <div class="backed">
        <h3>Projects You Are Backing</h3>

        <table>
          <tr>
              <th></th>
              <th>Creator</th>
              <th>Campaign</th>
              <th>Amount Pledged</th>
              <th>Days Left</th>
             
          </tr>
          @foreach($user->pledges as $pledge)
              <tr>
                  <td>
                      <img src="{{$pledge->campaign->image ? asset('storage/' . $pledge->campaign->image) : asset('/images/homies.jpg')}}" alt="">
                  </td>
                  <td>{{ $pledge->campaign->user->firstname }} {{ $pledge->campaign->user->sirname }}</td>
                  <td><a class="titlelink" href="/discover/{{$pledge->campaign->id}}">{{$pledge->campaign->title }}</a></td>
                  <td>{{ $pledge->amount }} Eth</td>
                  <td>{{ $pledge->campaign->daysLeft }}</td>
                  <td>  <a class="editlink" href="{{ route('view.certificate', ['id' => $pledge->id]) }}" target="_blank">View Certificate (PDF)</a> </td>
              </tr>
          @endforeach
      </table>
      

    </div>

    <div class="my-campaigns">
        <h3>Your Campaigns</h3>


        <table>
            <tr>
              <th></th>
              <th>Campaign Name</th>
              <th>Target</th>
              <th>Amount Pledged</th>
              <th>Days Left</th>
              <th></th>
              <th></th>
            </tr>
                    @foreach ($user->campaigns as $campaign)
            <tr>
                <td> <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}" alt=""></td>
                <td> <a class="titlelink" href="/discover/{{$campaign->id}}">{{ $campaign->title }}</a></td>
                <td>Eth: {{ $campaign->target }}</td>
                <td>{{ $campaign->pledges->sum('amount') }}</td>
                <td>{{ $campaign->daysLeft }}</td>
                @if (!auth()->user()->suspended == "1")
                <td> <a class="editlink" href="{{ route('campaign.edit', ['id' => $campaign->id]) }}">Edit</a> </td>
              <td>
                <form method="POST" action="{{ route('campaign.delete', ['campaign' => $campaign->id]) }}">
                @csrf
                @method('DELETE')
                <button class="text-red-500"><i class="fa fa-trash" aria-hidden="true"></i>  Delete</button>
              </form>
            </td>
            @else
            <td>No actions due to suspension</td>
            @endif
            </tr>
            @endforeach
          </table>

    </div>

    <div class="my-account">
        
        
        <div class="profile_image">
           
            <img src="{{$user->profile ? asset('storage/' . $user->profile) : asset('/images/homies.jpg')}}"  alt="">
           
             <a href="/message">View Messages</a>
        </div>
        <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <h3>My Account</h3>
            <div class="part">
                <label for="Email">Email</label>
                <input type="email" name="email" value="{{$user->email}}" placeholder="{{$user->email}}">
            </div>
            <div class="part">
                <label for="Username">First Name</label>
                <input type="text" name="name" value="{{$user->firstname}}" placeholder="{{$user->firstname}}">
            </div>
            <div class="part">
                <label for="Username">Sir Name</label>
                <input type="text" name="name" value="{{$user->sirname}}" placeholder="{{$user->sirname}}">
            </div>
            <div class="part">
                <label for="image">Profile</label>
                <input type="file" id="profile" name="profile" required>
            </div>
            <div class="part">
                <button type="submit">Update Profile</button>
            </div>
        </form>
        
       
    </div>

   </div>

</x-layout>