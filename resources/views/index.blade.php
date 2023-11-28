<x-layout>
  
    <section class="hero">
        <h1>Bring your creative project to life.</h1>
        <p>Alenderx helps starups, and other creators find the resources and support they need to make their ideas a reality.</p>
        <a href="/discover" class="cta-button">Let's Discover</a>
        <a href="#get-started" class="cta-button">Learn How to get Started</a>
      </section>
    
      <section class="featured-projects">
        <h2>Featured Projects</h2>
        @foreach ($mostBackedProjects as $campaign)
        @if (!$campaign->suspended == 1)
        <div class="project-card">
          <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}"  alt="{{ $campaign->title }}">
            <h3><a href="/discover/{{$campaign->id}}"> {{ $campaign->title }}</a></h3>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}</p>
        </div>
        @endif
        @endforeach

        <div class="get-started" id="get-started">
          <h2>Get Started with Alenderx</h2>
          <p>Ready to turn your vision into reality? Getting started with Alenderx is easy and rewarding. Follow these simple steps to begin your journey.</p>
          <ul class="steps-list">
            <li><strong>Step 1:</strong> Sign Up - Create your Alenderx account to join our community.</li>
            <li><strong>Step 2:</strong> Create your <a href="https://metamask.io/" target="_blank">Meta Mask Account</a></li>
            <li><strong>Step 3:</strong> Connect your Metamask Account by clicking connect wallet on the navigation bar</li>
            <li><strong>Step 4:</strong> Launch - With your idea and plan in place, launch your project on Alenderx to start receiving support.</li>
            <li><strong>Step 5:</strong> Pledge - Support.</li>
          </ul>
          
          
        </div>

      </section>

</x-layout>