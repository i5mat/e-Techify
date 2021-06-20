@component('mail::message')
# Order Summary

This is your order summary, hope that your items arrive safely.

@component('mail::button', ['url' => url('http://127.0.0.1:8000/order/receipt/'.$recipientInfo->order_id)])
    View Order Receipt
@endcomponent

@component('mail::table')
| Product | | Price | Qty |
|:----:| :----: | :----: | :----: |
@foreach($orderInfo as $item)
| <img src="http://127.0.0.1:8000/storage/product/{{ $item->product_image_path }}" style="width: 100px; height: 100px" alt="animated" /> | {{ $item->product_name }} | {{ $item->product_price }} | x{{ $item->product_order_quantity }} |
@endforeach
<p style="text-align: center">
    Payment made by : {{ $recipientInfo->payment_method }}
</p>
<h1 style="text-align:center">RM {{ number_format($recipientInfo->payment_total / 1) }}.00</h1>
@endcomponent

@component('mail::subcopy')
@endcomponent

Thanks ❤,<br>
{{ Auth::user()->name }}
@endcomponent
