<x-adminlayout>
   
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
  
</x-adminlayout>