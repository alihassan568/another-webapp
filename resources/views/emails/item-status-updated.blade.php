<h2>Your Item Has Been {{ ucfirst($status) }}</h2>
<p><strong>Item Name:</strong> {{ $item->name }}</p>
<p>Status: <strong>{{ ucfirst($status) }}</strong></p>

@if($status === 'rejected')
    <p><strong>Reason for Rejection:</strong></p>
    <p>{{ $item->rejection_reason }}</p>
@endif

<p>Thank you for using our platform.</p>
