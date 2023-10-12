<x-adminlayout>
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
                        <!-- You can add actions such as view, delete etc. here -->
                        <a href="{{ route('admin.reports.show', $report->id) }}">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-adminlayout>
