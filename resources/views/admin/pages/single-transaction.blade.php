<x-adminlayout>
    <div class="transaction-container">
        <h1>Transaction Details</h1>

        <div class="transaction-details">
            <p><strong>Amount:</strong> <span style="color: rgb(22, 192, 22)">Eth</span> {{ $transaction->amount }}</p>

            <p><strong>Sender:</strong> {{$transaction->user->ethereum_address}}</p>

            <p><strong>Recipient:</strong>{{$transaction->campaign->ethereum_address}}</p>

            <p><strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</x-adminlayout>
