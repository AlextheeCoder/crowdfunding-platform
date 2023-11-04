<x-adminlayout>
    <div class="alert-page">
        <div class="analytics-wrapper">
            <div class="pledge-analytics">
                <h4>Pledges Over Time</h4>
                <canvas id="pledgesOverTimeChart"></canvas>
            </div>
            <div class="pledge-analytics">
                <h4>Top Campaigns by Pledges</h4>
            <canvas id="topCampaignsByPledgesChart"></canvas>
            </div>
            <div class="pledge-analytics">
                <h4>Average Pledge Amount Over Time</h4>
            <canvas id="averagePledgeAmountChart"></canvas>
            </div>
        </div>

        <div class="content-section">
            <h2>Transaction Overview</h2>
            <a href="/transactions/exportcsv" class="btn btn-primary">Download as CSV</a>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sender Address</th>
                        <th>Receiver Address</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <tr>
                        @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{$transaction->user->ethereum_address}}</td>
                    <td>{{$transaction->campaign->ethereum_address}}</td>
                    <td style="color: rgb(22, 192, 22)">{{$transaction->amount}} ETH</td>
                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.transaction.show', $transaction->id) }}">View</a>
                    </td>
                </tr>
                @endforeach
                    </tr>
                  
                </tbody>
            </table>
        </div>
    </div>
    
    <script>

        //  Pledges Over Time
        let pledgesOverTimeLabels = @json($pledgesOverTime->pluck('date'));
        let pledgesOverTimeData = @json($pledgesOverTime->pluck('total_amount'));
        createLineChart('pledgesOverTimeChart', 'Pledges Over Time', pledgesOverTimeLabels, pledgesOverTimeData);
    
        //  Top Campaigns by Pledges
        let topCampaignsByPledgesLabels = @json($topCampaignsByPledges->pluck('title'));
        let topCampaignsByPledgesData = @json($topCampaignsByPledges->pluck('total_pledged'));
        createBarChart('topCampaignsByPledgesChart', 'Top Campaigns by Pledges', topCampaignsByPledgesLabels, topCampaignsByPledgesData);
    
        //  Average Pledge Amount Over Time
        let averagePledgeAmountLabels = @json($averagePledgeAmount->pluck('date'));
        let averagePledgeAmountData = @json($averagePledgeAmount->pluck('average_amount'));
        createLineChart('averagePledgeAmountChart', 'Average Pledge Amount Over Time', averagePledgeAmountLabels, averagePledgeAmountData);

        
    function createPieChart(canvasId, label, labels, data) {
        let ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],

                }]
            }
        });
    }

    function createBarChart(canvasId, label, labels, data) {
        let ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],

                }]
            }
        });
    }

    function createLineChart(canvasId, label, labels, data) {
        let ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                    fill: false
                }]
            }
        });
    }


    </script>
    

</x-adminlayout>
