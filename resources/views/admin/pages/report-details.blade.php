<x-adminlayout>
    <div class="report-detail-container">
        <div class="report-header">
            <h2>Report Details</h2>
        </div>

        <div class="profiles-section">
            <div class="profile reporter">
                <img src="{{$report->reporter->profile ? asset('storage/' . $report->reporter->profile) : asset('/images/homies.jpg')}}"  alt="">
                <h3>Reporter:</h3>
                <p>{{ $report->reporter->firstname }} {{ $report->reporter->sirname }}</p>
                <p style="color: #7f8c8d;font-size: 0.95em;line-height: 1.5;">{{ $report->reporter->email }}</p>

                <div class="profile-btn-a">
                    <a href="{{ route('user.manage', $report->reporter->id) }}">View Profile</a>
                </div>
               
                <!-- Add any other user details you want to display -->
            </div>
            <div class="profile reported">
                <img src="{{$report->reportedUser->profile ? asset('storage/' . $report->reportedUser->profile) : asset('/images/homies.jpg')}}"  alt="">
                <h3>Reported:</h3>
                <p>{{ $report->reportedUser->firstname }} {{ $report->reportedUser->sirname }}</p>
                <p style="color: #7f8c8d;font-size: 0.95em;line-height: 1.5;">{{ $report->reportedUser->email }}</p>

                <div class="profile-btn-a">
                    <a href="{{ route('user.manage', $report->reportedUser->id) }}">View Profile</a>
                </div>
                <!-- Add any other user details you want to display -->
            </div>
        </div>

        <div class="report-content">
            <h3>Reason for Reporting:</h3>
            <p>{{ $report->message }}</p>
        </div>
    </div>
</x-adminlayout>