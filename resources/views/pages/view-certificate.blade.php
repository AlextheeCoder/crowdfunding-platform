<style>
     table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      border: 2px solid #333;
    }
    
    /* Style for table header (th) */
    th {
      background-color: #f2f2f2;
      padding: 10px;
      border: 1px solid #333;
    }
    
    /* Style for table data (td) */
    td {
      padding: 10px;
      border: 1px solid #333;
    }
    
    /* Style for the first column of each row (labels) */
    tr td:first-child {
      font-weight: bold;
      color: black; /* Change the color as per your preference */
    }
    
    /* Style for the second column of each row (data) */
    tr td:nth-child(2) {
      text-align: center;
    }
    
    /* Style for the third column of each row (additional data) */
    tr td:nth-child(3) {
      text-align: center;
      color: #666; /* Change the color as per your preference */
    }
    
    /* Style for the h1 element */
    h1 {
      text-align: center;
      font-family: Arial, sans-serif;
      color: #333; /* Change the color as per your preference */
      margin-bottom: 20px;
      text-decoration: underline;
    }
    
    /* Optional: Add some space between rows */
    tr:not(:last-child) {
      margin-bottom: 10px;
    }
</style>

<div class="certificate">

    <h1>{{ $title }}</h1>

    

    <table>
        <tr style="font-style: bold">
            <td>Certificate for:</td>
            <td colspan="2">{{ $campaignTitle }}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td>Creator Name:</td>
            <td colspan="2">{{ $campaign->user->firstname }} {{ $campaign->user->sirname }}</td>
        </tr>
        <tr>
            <td>Investor Name:</td>
            <td colspan="2">{{ $investorName }} </td>
        </tr>
        <tr>
            <td>Amount in Eth:</td>
            <td colspan="2">{{ $amountEth }} Eth</td>
        </tr>
        <tr>
            <td>Campaign Address:</td>
            <td colspan="2">{{ $campaignAddress }}</td>
        </tr>
        <tr>
            <td>Investor Address:</td>
            <td colspan="2">{{ $investorAddress }}</td>
        </tr>
        <tr>
            <td>Date of Pledge:</td>
            <td colspan="2">{{ $pledgeDate }}</td>
        </tr>
    </table>
</div>
 

 