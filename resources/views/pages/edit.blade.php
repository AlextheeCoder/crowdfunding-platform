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
                <div id="quill-editor" style="height: 200px;">{!! $campaign->description !!}</div>
                <textarea class="tinymce-editor" id="description" name="description" rows="4" style="display:none;"></textarea>
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

  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script>
      var quill = new Quill('#quill-editor', {
          theme: 'snow'
      });

      document.getElementById('campaign-form').addEventListener('submit', function(event) {
          var quillContent = quill.root.innerHTML;
          if (quillContent.trim() === '' || quillContent === '<p><br></p>') { // Check if Quill editor is empty
              alert('Description is required!');
              event.preventDefault(); // Prevent form submission
              return;
          }
          document.getElementById('description').value = quillContent;
      });
  </script>
</x-layout>
