<x-adminlayout>
    <div class="campaign-details">
        <div class="campaign-image">
            <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}" alt="">
        </div>
        <div class="campaign-info">
           <a href="/discover/{{$campaign->id}}"> <h2>{{$campaign->title}}</h2></a>
            <div class="creator-info">
                <h4>Creator: <span>{{$campaign->user->firstname}} {{$campaign->user->sirname}}</span></h4>
                <a href="{{ route('user.manage', $campaign->user->id) }}" class="view-creator-btn">View creator</a>
            </div>
        </div>
        <div class="campaign-stats">
            <p><strong>Deadline:</strong> <span>{{$campaign->deadline}}</span></p>
            <p><strong>Target:</strong> <span>{{$campaign->target}}</span></p>
            <p><strong>Number of Pledges:</strong><span> {{$campaign->PledgeCount}}</span> </p>
            <p><strong>Total Pledged:</strong><span> {{ $campaign->totalPledged }}</span> </p>
        </div>
        <div class="campaign-actions">
            <button class="suspend-btn">Suspend</button>
        </div>
    </div>
</x-adminlayout>
