<x-layout>
    <div class="createcampaign">
        <div class="createform">
            <form method="POST" action="{{ route('campaign.update', ['campaign' => $campaign->id]) }}" enctype="multipart/form-data" id="campaign-form">
                @csrf
                @method('PUT')
                <div class="part">
                  <label for="title">Title</label>
                  <input type="text" id="title" name="title" required value="{{$campaign->title}}">
                </div>

                <div class="part">
                  <label for="category">Catergory</label>
                  <select name="category" id="category">
                    <option value="technology">Technology</option>
                    <option value="social">Social</option>
                    <option value="lifestyle">Lifestyle</option>
                    <option value="business">Business</option>
                  </select>
                </div>
                <div class="part">
                  <label for="description">Description</label>
                  <textarea class="tinymce-editor" id="description" name="description" rows="4" required>{{$campaign->description}}</textarea>
                </div>
            
                <div class="part"> 
                  <label for="target">Target in Eth</label>
                  <input type="number" id="target" name="target" step="0.01" required value="{{$campaign->target}}">
                </div>
            
                <div class="part">
                  <label for="date">Deadline</label>
                  <input type="date" id="date" name="date" required value="{{$campaign->deadline}}">
                </div>
            
                <div class="part">
                  <label for="image">Image</label>
                  <input type="file" id="image" name="image">
                </div>
                <div class="part">
                  <button type="submit" id="edit-campaign-button">Edit Campaign</button>
                </div>
              </form>
        </div>
    </div>
</x-layout>
