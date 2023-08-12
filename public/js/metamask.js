document.addEventListener('DOMContentLoaded', async function() {
  const connectWalletForm = document.getElementById('connect-wallet-form');

  connectWalletForm.addEventListener('submit', async function(event) {
    event.preventDefault();

    try {
      // Prompt Metamask authorization
      const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
      const userAccount = accounts[0];

      // Add the Ethereum address to the form data
      const formData = new FormData(connectWalletForm);
      formData.append('ethereum_address', userAccount);

      // Submit the form data to the server
      const route = "/store/address"; // Replace with the actual URL of your 'storeAddress' route

      try {
        const response = await axios.post(route, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        // Handle the response or update the UI accordingly
        console.log('Server response:', response.data);
        alert('Wallet Connected!');
        window.location.href = '/';
      } catch (error) {
        // Handle errors
        console.error('Error:', error);
      }


      // Reset the form
      connectWalletForm.reset();
    } catch (error) {
      // Handle errors
      console.error('Error:', error);
    }
  });
});
