<x-adminlayout>
    <div class="user-managment">

        
        <div class="analytics-wrapper">
            <div class="user-analytics">
                <h2>User Analytics</h2>
                <h4>Gender Distribution</h4>
                <canvas id="genderDistributionChart"></canvas>
            </div>
            <div class="user-analytics">
                <h4>Age Distribution</h4>
                <canvas id="ageDistributionChart"></canvas>
            </div>
        </div>

        <div class="table-container">
            <h2>USERS</h2>
            <a href="/users/exportcsv" class="btn btn-primary">Download as CSV</a>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Ethereum Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Date Joined</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                    <!-- Row 1 -->
                    @foreach($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td><img src="{{$user->profile ? asset('storage/' . $user->profile) : asset('/images/homies.jpg')}}"  alt=""> <span>{{ $user->firstname }} {{ $user->sirname }}</span></td>
                        <td>{{$user->ethereum_address}}</td>
                        <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->diffInYears(\Carbon\Carbon::now()) : 'N/A' }}</td>
                        <td>{{$user->gender}}</td>
                        <td>{{$user->created_at->diffForHumans()}}</td>
                        <td>{{$user->role}}</td>
                        <td><a href="{{ route('user.manage', $user->id) }}" class="details-btn">Details</a></td>
                    </tr>
                    @endforeach
                    <!-- Additional rows can be added similarly -->
                </tbody>
            </table>
        </div>
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
    </script>
    
</x-adminlayout>

