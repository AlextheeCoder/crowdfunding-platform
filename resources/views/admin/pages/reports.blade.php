<x-adminlayout>
    <div class="alert-page">
        <div class="report-section">
            <h2>Reports Overview</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reported User</th>
                        <th>Reported By</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $report->reportedUser->firstname }}</td>
                        <td>{{ $report->reporter->firstname }}</td>
                        <td>{{ $report->message }}</td>
                        <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                           
                            <a href="{{ route('admin.reports.show', $report->id) }}">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <div class="content-section">
            <h2>Issues Overview</h2>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                   
                    <tr>
                        @foreach($issues as $issue)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{$issue->user->profile ? asset('storage/' . $issue->user->profile) : asset('/images/homies.jpg')}}"  alt=""> <span>{{ $issue->user->firstname }} {{ $issue->user->sirname }}</td>
                    <td>{{ $issue->issue_type }}</td>
                    <td>{{ Str::limit($issue->description, 50) }}</td>
                    <td class="status {{ strtolower($issue->status) }}">{{ $issue->status }}</td>
                    <td>{{ $issue->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                       
                        <a href="{{ route('admin.issues.show', $issue->id) }}">View</a>
                    </td>
                </tr>
                @endforeach
                    </tr>
                  
                </tbody>
            </table>
        </div>
    </div>
    
</x-adminlayout>
