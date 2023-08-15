<x-layout>
  <div class="createcampaign" style="margin-bottom: 100px">
      <div class="createform">
          <form method="POST" action="{{ route('campaign.create') }}" enctype="multipart/form-data" id="campaign-form">
              @csrf
          
              <div class="part">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
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
                <textarea  class="tinymce-editor" id="description" name="description" rows="4" required></textarea>
              </div>
          
              <div class="part">
                <label for="target">Target in Eth</label>
                <input type="number" id="target" name="target" step="0.01"  required>
              </div>
          
              <div class="part">
                <label for="date">Deadline</label>
                <input type="date" id="date" name="date" required>
              </div>
          
              <div class="part">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" required>
              </div>
              <div class="part">
                <button type="submit" id="create-campaign-button">Create Campaign</button>
              </div>
            </form>
      </div>
      <div class="overlay" id="overlay" style="display: none"></div>
      <div class="loading-spinner" id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
        <div class="spinner-text" style="border-top: 10px">Loading...</div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/web3@1.5.3/dist/web3.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    (async function() {
  // Check if MetaMask is installed and enable Ethereum provider
  if (typeof window.ethereum !== 'undefined') {
    await window.ethereum.enable();
  }

  // Create a Web3 instance
  const web3 = new Web3(window.ethereum);

  // Get the current user's Ethereum account address
  const accounts = await web3.eth.getAccounts();
  const userAddress = accounts[0];

  // Get the form and submit button
  const form = document.getElementById('campaign-form');
  const submitButton = document.getElementById('create-campaign-button');

  // Get the loading spinner element
  const loadingSpinner = document.getElementById('loading-spinner');
  const overlay=document.getElementById('overlay');

  // Add event listener to the form submission
  form.addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent form submission

    // Show the loading spinner
    loadingSpinner.style.display = 'block';
    overlay.style.display='block';

    // Disable the submit button to prevent multiple submissions
    submitButton.disabled = true;

    try {
      // Get the form data using the FormData API
      const formData = new FormData(form);
      const title = formData.get('title');
      const categorySelect = document.getElementById('category');
      const selectedCategory = categorySelect.options[categorySelect.selectedIndex].value;
      const description = formData.get('description');
      const target = formData.get('target');
      const date = formData.get('date');
      const fileInput = document.getElementById('image'); // Get the file input element
      const file = fileInput.files[0]; // Get the selected file

      // Construct the transaction data using the form field values
      const transactionData = {
        title: title,
        description: description,
        target: target,
        date: date,
      };

      // Convert transactionData to JSON string and encode in HEX format
      const transactionDataHex = web3.utils.asciiToHex(JSON.stringify(transactionData));

      // Prepare the transaction parameters
      const transactionParameters = {
        from: userAddress,
        to: '0x3DCbf1c8FDA837ee43621323812E2c9E6AEFc532', // Replace with your actual contract address
        value: web3.utils.toWei('0.1', 'ether'), // Example: sending 0.1 ETH
        data: transactionDataHex
      };

      // Send the transaction
      try {
        const receipt = await web3.eth.sendTransaction(transactionParameters);

        // Transaction successful
        alert('Transaction successful!'); // Display success message or redirect

        // Create a new FormData instance and append the file to it
        const formDataWithFile = new FormData();
        formDataWithFile.append('title', title);
        formDataWithFile.append('description', description);
        formDataWithFile.append('target', target);
        formDataWithFile.append('date', date);
        formDataWithFile.append('image', file);
        formDataWithFile.append('category', selectedCategory);


        // Send the campaign data to the server for storage
        const route = "{{ route('campaign.create') }}";
        const response = await axios.post(route, formDataWithFile, {
          headers: {
            'Content-Type': 'multipart/form-data' // Set the correct content type for file uploads
          }
        });

        // Check if the response was successful
        if (response.status === 200) {
          // Redirect to the specified URL
          window.location.href = '/'; // Replace with the desired URL
        } else {
          console.log(response.data); // Optional: Log the server response
          alert('Campaign created successfully, but redirection failed.'); // Display a message indicating redirection failure
        }

      } catch (error) {
        // Transaction failed
        console.error(error);
        alert('Transaction failed. Please try again.'); // Display error message
      }

    } catch (error) {
      // Handle errors
      console.error('Error:', error);
    }

    // Hide the loading spinner
    loadingSpinner.style.display = 'none';

    // Enable the submit button again
    submitButton.disabled = false;
  });
})();

  </script>
  

</x-layout>
