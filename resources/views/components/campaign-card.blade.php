@props(['campaign'])

<x-card>
    <div class="card">
      <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}"  alt="{{ $campaign->title }}">
        
        <h2 class="campaign-title">
            <a href="/discover/{{$campaign->id}}">{{$campaign->title}} </a>
        </h2>

        <p class="campaign-description">{{$campaign->description}}</p>
        <div class="campaign-stats">
          <div class="stat">
            <span class="stat-value">{{ $campaign->uniqueInvestorsCount }}</span>

            <span class="stat-label">Backers</span>
          </div>
          <div class="stat">
            <span class="stat-value">Eth: {{ $campaign->totalPledged }}</span>
            <span class="stat-label">Pledged</span>
          </div>
          <div class="stat">
            <span class="stat-value">{{ $campaign->daysLeft }}</span>
            <span class="stat-label">Days to Go </span>
          </div>
        </div>
      </div> 
</x-card>