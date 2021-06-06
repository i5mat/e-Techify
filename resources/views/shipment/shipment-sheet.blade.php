<!DOCTYPE html>
<html lang="en">
<head>
    <title>Request Shipment #{{ request()->route('id') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Override some Bootstrap CDN styles - normally you should apply these through your Bootstrap variables / sass -->
    <style>
        body { font-family: "Roboto", serif; font-size: 0.8rem; font-weight: 400; line-height: 1.4; color: #000000; }
        h1, h2, h4, h5 { font-weight: 700; color: #000000; }
        h1 { font-size: 2rem; }
        h2 { font-size: 1.6rem; }
        h4 { font-size: 1.2rem; }
        h5 { font-size: 1rem; }
        .table { color: #000; }
        .table td, .table th { border-top: 1px solid #000; }
        .table thead th { vertical-align: bottom; border-bottom: 2px solid #000; }

        @page {
            margin-top: 2.5cm;
            margin-bottom: 2.5cm;
        }

        @page :first {
            margin-top: 0;
            margin-bottom: 2.5cm;
        }
    </style>

    <script>
        myFunction();
        function myFunction(){
            window.print();
        }
    </script>

</head>
<body>

<div style="background-color: #000000; height: 10px;"></div>

<div class="container-fluid pt-2 pt-md-4 px-md-5">

    <!-- Invoice heading -->

    <table class="table table-borderless">
        <tbody>
        <tr>
            <td class="border-0">
                <div class="row">
                    <div class="col-md text-center text-md-left mb-3 mb-md-0">
                        <img class="logo img-fluid mb-3" src="/image/XT-logo.png" style="max-height: 140px;"/>
                        <br>

                        <h2 class="mb-1">Xmiryna Technology [SA0546866-M]</h2>
                        No. 79 Jalan Taman Melati 1,<br>
                        Taman Melati, Setapak,<br>
                        53100, Kuala Lumpur<br>
                        xmiryna.tech@outlook.com / <b>+(60) 17-217 8319</b><br>
                        <strong>xmiryna.com.my</strong>
                    </div>

                    <div class="col text-center text-md-right">

                        <!-- Dont' display Bill To on mobile -->
                        <span class="d-none d-md-block">
                            <h1>Request Shipment</h1>
                            <h2>#{{ request()->route('id') }}</h2>
                        </span>

                        Status
                        <h2>
                            @if($recipientInfo->status == 'Shipped')
                                <span class="badge bg-primary" style="color: black">{{ $recipientInfo->status }}</span>
                            @elseif($recipientInfo->status == 'Waiting Approval')
                                <span class="badge bg-warning" style="color: black">{{ $recipientInfo->status }}</span>
                            @elseif($recipientInfo->status == 'Approved')
                                <span class="badge bg-success" style="color: black">{{ $recipientInfo->status }}</span>
                            @elseif($recipientInfo->status == 'Requested')
                                <span class="badge bg-dark" style="color: white">{{ $recipientInfo->status }}</span>
                            @endif
                        </h2>
                        <br>
                        Requested On
                        <h2>{{ date('d M Y', strtotime($recipientInfo->created_at)) }}</h2><br>
                        Tracking No.
                        <h2>
                            @if(isset($recipientInfo->tracking_no))
                                #{{ $recipientInfo->tracking_no }}
                            @else
                                Not Available
                            @endif
                        </h2>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <table class="table">
        <thead>
        <tr>
            <th>Summary</th>
            <th class="text-center">Quantity</th>
            <th class="text-right">Unit Price</th>
            <th class="text-right">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $i)
        <tr>
            <td>
                <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                <h5 class="mb-1">{{ $i->product_name }}</h5>
                {{ $i->product_warranty_duration }} years local distributor warranty.
            </td>
            <td class="font-weight-bold align-middle text-center text-nowrap">x{{ $i->product_order_quantity }}</td>
            <td class="font-weight-bold align-middle text-right text-nowrap">RM {{ number_format($i->product_price / 1) }}.00</td>
            <td class="font-weight-bold align-middle text-right text-nowrap" id="total_per_item{{ $loop->iteration }}">{{ $i->product_price * $i->product_order_quantity }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-right border-0 pt-4">Total <h5 id="total"></h5></td>
        </tr>
    </table>

    <!-- Thank you note -->

    <div class="text-center">
        Remark
    </div>
    <h2 class="text-center pt-2">
        {{ $recipientInfo->remark }}
    </h2>
</div>

<script>
    var total_items = {{ $items->count() }};
    var merchandise_total = 0;

    for (i = 1; i <= total_items; i++) {
        var formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'MYR',
        });
        var total_price_item = $("#total_per_item"+ i).text();

        merchandise_total += parseInt(total_price_item);

        document.getElementById('total').innerHTML = formatter.format(merchandise_total);
        document.getElementById('total_per_item'+ i).innerHTML = formatter.format(total_price_item);
    }
</script>
</body>
