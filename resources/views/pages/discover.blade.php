<x-layout>
   <div class="discover">
       <div class="tttext">
       </div>
       <div class="abtext">
           <img src="images/world.jpg" alt="">
           <div class="tttext">

            <h2>Posibbilities are Limitless</h2>
               <p>Unlock the limitless wellspring of inspiration that awaits you,
                   as we embark on a transformative journey together. Let our shared
                   experiences and creative vision kindle the spark within, igniting
                   a world of possibilities. Find inspiration with us and let your
                   imagination soar to new heights</p>

             
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
            <x-campaign-card :campaign="$campaign" />
            @endforeach
            @else
            <p>No campaigns found</p>
            @endunless
          </div>
       </div>
     
      
   </div>

      
</x-layout>
