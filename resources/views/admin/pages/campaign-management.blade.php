<x-adminlayout>
    <div class="campaign-management">
        <div class="analytics-wrapper">
            <div class="campaign-analytics">
                <h4>Campaigns Created Over Time</h4>
                <canvas id="campaignsCreatedChart"></canvas>
            </div>
            <div class="campaign-analytics">
                <h4>Top Campaign Categories</h4>
                <canvas id="topCampaignCategoriesChart"></canvas>
            </div>
            <div class="campaign-analytics">
                <h4>Campaigns by Offering Type</h4>
                <canvas id="campaignsByOfferingTypeChart"></canvas>
            </div>
            <div class="campaign-analytics">
                <h4>Total Valuation Over Time</h4>
                <canvas id="totalValuationChart"></canvas>
            </div>
            <div class="campaign-analytics">
                <h4>Active vs Suspended Campaigns</h4>
                <canvas id="activeVsSuspendedCampaignsChart"></canvas>
            </div>
        </div>

        <div class="table-container">
            <h2>Campaigns</h2>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Ethereum Address</th>
                        <th>Deadline</th>
                        <th>Owner</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <!-- Row 1 -->
                    @foreach ($campaigns as $campaign )
                    <tr>
                        <td># {{ $campaign->id }}</td>
                        <td> <span> {{$campaign->title}}</span></td>
                        <td>{{$campaign->ethereum_address}}</td>
                        <td>{{ $campaign->deadline}}</td>
                        <td>{{$campaign->user->firstname}} {{$campaign->user->sirname}}</td>
                        <td>{{$campaign->created_at->diffForHumans()}}</td>
                        <td><a href="{{ route('campaign.manage', $campaign->id) }}" class="details-btn">Details</a></td>
                    </tr>
                    @endforeach
                    <!-- Additional rows can be added similarly -->
                </tbody>
            </table>
        </div>
        </div>
    </div>

    <script>
    
    
        // 3. Campaigns Created Over Time
        let campaignsCreatedLabels = @json($campaignsCreated->pluck('date'));
        let campaignsCreatedData = @json($campaignsCreated->pluck('count'));
        createLineChart('campaignsCreatedChart', 'Campaigns Created Over Time', campaignsCreatedLabels, campaignsCreatedData);
    
        // 4. Top Campaign Categories
        let topCampaignCategoriesLabels = @json($topCampaignCategories->pluck('category'));
        let topCampaignCategoriesData = @json($topCampaignCategories->pluck('count'));
        createBarChart('topCampaignCategoriesChart', 'Top Campaign Categories', topCampaignCategoriesLabels, topCampaignCategoriesData);
    
        // 5. Campaigns by Offering Type
        let campaignsByOfferingTypeLabels = @json($campaignsByOfferingType->pluck('offering_type'));
        let campaignsByOfferingTypeData = @json($campaignsByOfferingType->pluck('count'));
        createBarChart('campaignsByOfferingTypeChart', 'Campaigns by Offering Type', campaignsByOfferingTypeLabels, campaignsByOfferingTypeData);
    
        // 6. Total Valuation Over Time
        let totalValuationLabels = @json($totalValuation->pluck('date'));
        let totalValuationData = @json($totalValuation->pluck('total_valuation'));
        createLineChart('totalValuationChart', 'Total Valuation Over Time', totalValuationLabels, totalValuationData);
    
        // 7. Active vs Suspended Campaigns
        let activeVsSuspendedCampaignsLabels = @json($activeVsSuspendedCampaigns->pluck('suspended'));
        let activeVsSuspendedCampaignsData = @json($activeVsSuspendedCampaigns->pluck('count'));
        createBarChart('activeVsSuspendedCampaignsChart', 'Active vs Suspended Campaigns', activeVsSuspendedCampaignsLabels, activeVsSuspendedCampaignsData);
    
      
        
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