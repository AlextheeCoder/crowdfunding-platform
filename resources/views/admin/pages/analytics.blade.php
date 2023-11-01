<x-adminlayout>
    <div class="analytics-container">

        <div class="user-analytics">
            <h2>User Anlytics</h2>
            <h4>Gender Distribution</h4>
            <canvas id="genderDistributionChart"></canvas>
            <h4>Age Distribution</h4>
            <canvas id="ageDistributionChart"></canvas>
        </div>

       
        <div class="campaign-analytics">
            <h2>Campaign Anlytics</h2>
            <h4>Campaigns Created Over Time</h4>
            <canvas id="campaignsCreatedChart"></canvas>

            <h4>Top Campaign Categories</h4>
            <canvas id="topCampaignCategoriesChart"></canvas>
            <h4>Campaigns by Offering Type</h4>
            <canvas id="campaignsByOfferingTypeChart"></canvas>

            <h4>Total Valuation Over Time</h4>
            <canvas id="totalValuationChart"></canvas>
            <h4>Active vs Suspended Campaigns</h4>
            <canvas id="activeVsSuspendedCampaignsChart"></canvas>

        </div>
       
        <div>
            <h2>Pledge Anlytics</h2>
            <h4>Pledges Over Time</h4>
            <canvas id="pledgesOverTimeChart"></canvas>

            <h4>Top Campaigns by Pledges</h4>
            <canvas id="topCampaignsByPledgesChart"></canvas>

            <h4>Average Pledge Amount Over Time</h4>
            <canvas id="averagePledgeAmountChart"></canvas>
        </div>
    </div>
    

    <script>
        // 1. Gender Distribution Chart
        let genderLabels = @json($genderDistribution->pluck('gender'));
        let genderData = @json($genderDistribution->pluck('count'));
        createPieChart('genderDistributionChart', 'Gender Distribution', genderLabels, genderData);
    
        // 2. Age Distribution Chart
        let ageLabels = @json($ageDistribution->pluck('age_bracket'));
        let ageData = @json($ageDistribution->pluck('count'));
        createBarChart('ageDistributionChart', 'Age Distribution', ageLabels, ageData);
    
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
    
        // 8. Pledges Over Time
        let pledgesOverTimeLabels = @json($pledgesOverTime->pluck('date'));
        let pledgesOverTimeData = @json($pledgesOverTime->pluck('total_amount'));
        createLineChart('pledgesOverTimeChart', 'Pledges Over Time', pledgesOverTimeLabels, pledgesOverTimeData);
    
        // 9. Top Campaigns by Pledges
        let topCampaignsByPledgesLabels = @json($topCampaignsByPledges->pluck('title'));
        let topCampaignsByPledgesData = @json($topCampaignsByPledges->pluck('total_pledged'));
        createBarChart('topCampaignsByPledgesChart', 'Top Campaigns by Pledges', topCampaignsByPledgesLabels, topCampaignsByPledgesData);
    
        // 10. Average Pledge Amount Over Time
        let averagePledgeAmountLabels = @json($averagePledgeAmount->pluck('date'));
        let averagePledgeAmountData = @json($averagePledgeAmount->pluck('average_amount'));
        createLineChart('averagePledgeAmountChart', 'Average Pledge Amount Over Time', averagePledgeAmountLabels, averagePledgeAmountData);
    
        // ... Rest of the functions remain unchanged ...

        
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