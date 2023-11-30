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
                <div id="description" style="height:150px;"></div> <!-- Quill requires a div, not a textarea -->
                <textarea name="description" style="display:none;"></textarea> <!-- Hidden textarea to store the actual data -->
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
                <label for="offering_type">Offering Type</label>
                <select name="offering_type" id="offering_type">
                  <option value="equity">Equity</option>
                  <option value="crowdfunding">Crowdfunding</option>
                  <option value="product_crowdfunding">Product Crowdfunding</option>
                </select>
              </div>
              
              <div class="equity-fields">
                <div class="part">
                  <label for="price_per_share">Price Per Share (in ETH)</label>
                  <input type="number" id="price_per_share" name="price_per_share" step="0.01">
                </div>
              
                <div class="part">
                  <label for="valuation">Valuation (ETH)</label>
                  <input type="number" id="valuation" name="valuation" step="0.01">
                </div>
              
                <div class="part">
                  <label for="min_investment">Minimum Investment (in ETH)</label>
                  <input type="number" id="min_investment" name="min_investment" step="0.01">
                </div>
              </div>
              <div class="part asset-type-field">
                <label for="asset_type">Asset Type</label>
                <select name="asset_type" id="asset_type">
                  <option value="common_stock">Common Stock</option>
                  <option value="commodities">Commodities</option>
                  <option value="intellectual_property">Intellectual Property</option>
                </select>
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
        <div class="spinner-text" style="margin-top: 10px">Creating Campaign...</div>
      </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/web3@1.5.3/dist/web3.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      const offeringTypeSelect = document.getElementById('offering_type');
      const equityFields = document.querySelector('.equity-fields');
      const assetTypeField = document.querySelector('.asset-type-field');
      const assetTypeSelect = document.getElementById('asset_type');
  
      offeringTypeSelect.addEventListener('change', () => {
        if (offeringTypeSelect.value === 'equity') {
          equityFields.style.display = 'block';
          assetTypeField.style.display = 'block';
          assetTypeSelect.innerHTML = `
            <option value="common_stock">Common Stock</option>
          `;
        } else if (offeringTypeSelect.value === 'product_crowdfunding') {
          equityFields.style.display = 'none';
          assetTypeField.style.display = 'block';
          assetTypeSelect.innerHTML = `
            <option value="commodities">Commodities</option>
            <option value="intellectual_property">Intellectual Property</option>
          `;
        } else {
          equityFields.style.display = 'none';
          assetTypeField.style.display = 'none';
        }
      });
  
      // Triggering the change event to set the initial state
      offeringTypeSelect.dispatchEvent(new Event('change'));
    });
  </script>
  


  <script>
    var quill = new Quill('#description', {
        theme: 'snow'
    });
    
    (async function() {
        // Check if MetaMask is installed and enable Ethereum provider
        if (typeof window.ethereum !== 'undefined') {
            await window.ethereum.enable();
        }

        // Creating a Web3 instance
        const web3 = new Web3(window.ethereum);

        // Get the current user's Ethereum account address
        const accounts = await web3.eth.getAccounts();
        const userAddress = accounts[0];

        // Get the form and submit button
        const form = document.getElementById('campaign-form');
        const submitButton = document.getElementById('create-campaign-button');

        // Get the loading spinner element
        const loadingSpinner = document.getElementById('loading-spinner');
        const overlay = document.getElementById('overlay');

        // Add event listener to the form submission
        form.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent form submission

            // Update the hidden textarea with Quill's content
            document.querySelector('textarea[name=description]').value = quill.root.innerHTML;

            // Show the loading spinner
            loadingSpinner.style.display = 'block';
            overlay.style.display = 'block';

            // Disabling the submit button to prevent multiple submissions
            submitButton.disabled = true;

            try {
                // Getting the form data using the FormData API
                const formData = new FormData(form);
                const title = formData.get('title');
                const categorySelect = document.getElementById('category');
                const selectedCategory = categorySelect.options[categorySelect.selectedIndex].value;
                const description = formData.get('description');
                const target = formData.get('target');
                const date = formData.get('date');
                const offeringType = formData.get('offering_type');
                const assetType = formData.get('asset_type');
                const pricePerShare = formData.get('price_per_share');
                const valuation = formData.get('valuation');
                const minInvestment = formData.get('min_investment');
                const fileInput = document.getElementById('image');
                const file = fileInput.files[0];

                // Construct the transaction data using the form field values
                const transactionData = {
                  title: title,
                  description: description,
                  target: target,
                  date: date,
                  offering_type: offeringType,
                  asset_type: assetType,
                  price_per_share: pricePerShare,
                  valuation: valuation,
                  min_investment: minInvestment,
                };

                // Convert transactionData to JSON string and encode in HEX format
                const transactionDataHex = web3.utils.asciiToHex(JSON.stringify(transactionData));

                // Prepare the transaction parameters
                const transactionParameters = {
                    from: userAddress,
                    to: '0x3DCbf1c8FDA837ee43621323812E2c9E6AEFc532',
                    value: web3.utils.toWei('0.1', 'ether'),
                    data: transactionDataHex
                };

                // Send the transaction
                try {
                    const receipt = await web3.eth.sendTransaction(transactionParameters);

                    // Transaction successful
                    alert('Transaction successful!'); 

                    // Creating a new FormData instance and append the file to it
                    const formDataWithFile = new FormData();
                    formDataWithFile.append('title', title);
                    formDataWithFile.append('description', description);
                    formDataWithFile.append('target', target);
                    formDataWithFile.append('date', date);
                    formDataWithFile.append('offering_type', offeringType);
                    formDataWithFile.append('asset_type', assetType);
                    formDataWithFile.append('price_per_share', pricePerShare);
                    formDataWithFile.append('valuation', valuation);
                    formDataWithFile.append('min_investment', minInvestment);
                    formDataWithFile.append('image', file);
                    formDataWithFile.append('category', selectedCategory);


                    // Sending the campaign data to the server for storage
                    const route = "{{ route('campaign.create') }}";
                    const response = await axios.post(route, formDataWithFile, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    // Check if the response was successful
                    if (response.status === 200) {
                        // Redirect to URL
                        window.location.href = '/'; 
                    } else {
                        console.log(response.data); //Log the server response
                        alert('Campaign created successfully, but redirection failed.'); // Displaying a message indicating redirection failure
                    }

                } catch (error) {
                    // Transaction failed
                    console.error(error);
                    alert('Transaction failed. Please try again.'); // Display error message
                }

            } catch (error) {
                // Log errors
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
