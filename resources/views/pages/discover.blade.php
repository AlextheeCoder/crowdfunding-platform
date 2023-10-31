<x-layout>
  
   <div class="discover">
       
       <div class="abtext">
          
           <div class="tttext">

            <h2>Posibbilities are Limitless</h2>
               <p style="margin: 20px">
                Fund new and groundbreaking projects
              </p>

             
           </div>
           <div class="filter">
           
                                       
            <h4>Search: </h4>

            
            <form action="/discover" method="get">
               <div class="search-container">
                   <button type="submit"><i class="fa fa-search"></i></button>
                   <input type="search" name="search" id="searchInput" placeholder="Search...">
                  
               </div>
           </form>
           
               
        </div>
       </div>
       <div class="bottom-section">
         <div class="filters">
            <h2>Filter Options</h2>
            
            <label for="category">Category:</label>
            <form action="/discover">
              <select name="category" id="category">
                <option value="all">All Categories</option>
                <option value="Technology">Technology</option>
                <option value="Social">Social</option>
                <option value="Business">Business</option>
                <option value="lifestyle">Life Style</option>
              </select>
            <label for="sort_by">Sort By:</label>
            <select name="sort_by" id="sort_by">
              <option value="featured">Featured</option>
              <option value="latest">Latest</option>
              <option value="popular">Popular</option>
              <option value="price_low_to_high">Target: Low to High</option>
              <option value="price_high_to_low">Target: High to Low</option>
            </select>
          
            <button type="submit">Apply Filters</button>
          </form>
          </div>
          
          <div class="card-container">
            @unless(count($campaigns) == 0)
            @foreach($campaigns as $campaign)
            @if (!$campaign->suspended == 1)
            <x-campaign-card :campaign="$campaign" />
            @endif
            @endforeach
            @else
            <p>No campaigns found</p>
            @endunless
          </div>
        

         
       </div>
       <bootstrap-isolated>
        <div class="pagination d-flex justify-content-center">
          {{ $campaigns->appends(request()->query())->links('pagination::bootstrap-5') }}
      </div>
      
      </bootstrap-isolated>
   </div>

   <script>
    class BootstrapIsolated extends HTMLElement {
      connectedCallback() {
        const content = this.innerHTML;
        this.attachShadow({ mode: 'open' }).innerHTML = `
          <!-- Add Bootstrap CSS -->
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
          ${content}
        `;
      }
    }
  
    // Define the custom element
    customElements.define('bootstrap-isolated', BootstrapIsolated);
</script>

</x-layout>
