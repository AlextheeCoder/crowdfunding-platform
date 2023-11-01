<x-adminlayout>
    <div class="alert-page">
        <div class="content-section">
            <h2>Transaction Overview</h2>
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
    
</x-adminlayout>
