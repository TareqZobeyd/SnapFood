<p>Dear {{ $order->user->name }},</p>

<p>Your order has been sent successfully!</p>

<p>Order Details:</p>
<ul>
    @foreach($order->foods as $food)
        <li>{{ $food->name }} - Quantity: {{ $food->pivot->count }}</li>
    @endforeach
</ul>

<p>Total Amount: ${{ $order->total_amount }}</p>

<p>Thank you for choosing our service!</p>
