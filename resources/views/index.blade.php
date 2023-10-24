<x-layout>
  
    <section class="hero">
        <h1>Bring your creative project to life.</h1>
        <p>Alenderx helps starups, and other creators find the resources and support they need to make their ideas a reality.</p>
        <a href="/discover" class="cta-button">Let's Discover</a>
      </section>
    
      <section class="featured-projects">
        <h2>Featured Projects</h2>
        @foreach ($mostBackedProjects as $campaign)
        <div class="project-card">
          <img src="{{$campaign->image ? asset('storage/' . $campaign->image) : asset('/images/homies.jpg')}}"  alt="{{ $campaign->title }}">
            <h3><a href="/discover/{{$campaign->id}}"> {{ $campaign->title }}</a></h3>
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($campaign->description), 100) }}</p>
        </div>
        @endforeach
      </section>

</x-layout>