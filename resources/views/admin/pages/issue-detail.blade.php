<x-adminlayout>
    <div class="issue-detail-section">
        <h2>Issue Details</h2>

        <div class="user-info">
            <h3>User Information</h3>
            <p><strong>Name:</strong> {{ $issue->user->firstname }} {{ $issue->user->sirname }}</p>
            <p><strong>Email:</strong> {{ $issue->user->email }}</p>
            <p><strong>Date Joined:</strong> {{ $issue->user->created_at->format('Y-m-d') }}</p>
            <a href="{{ route('user.manage', $issue->user->id) }}">View User</a>
        </div>

        <div class="issue-info">
            <h3>Issue Details</h3>
            <p><strong>Title:</strong> {{ $issue->title }}</p>
            <p><strong>Description:</strong> {{ $issue->description }}</p>
            <p><strong>Submitted On:</strong> {{ $issue->created_at->format('Y-m-d H:i') }}</p>
        </div>

        <div class="actions">

            @if ($issue->status == "completed")
                <p>This was completed</p>

                @else

                <form action="{{ route('admin.issues.suspend', $issue->id) }}" method="post">
                    @csrf
                    <button type="submit">Suspend User</button>
                </form>
    
                <form action="{{ route('admin.issues.reinstate', $issue->id) }}" method="post">
                    @csrf
                    <button type="submit">Reinstate User</button>
                </form>

            @endif
          

        </div>
    </div>
</x-adminlayout>
