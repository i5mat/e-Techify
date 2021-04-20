<!DOCTYPE html>
<html lang="en">
<head>
    <title>Responsive Bootstrap Invoice Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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
                        xmiryna.tech@outlook.com / +(60) 17-217 8319<br>
                        <strong>xmiryna.com.my</strong>
                    </div>

                    <div class="col text-center text-md-right">

                        <!-- Dont' display Bill To on mobile -->
                        <span class="d-none d-md-block">
                            <h1>Billed To</h1>
                        </span>

                        <h4 class="mb-0">{{ $recipientInfo->name }}</h4>

                        {!! wordwrap($recipientInfo->address, 40, "<br>\n") !!} <br/>
                        {{ Auth::user()->email }}<br/>

                        <h5 class="mb-0 mt-3">{{ date('d M Y') }}</h5>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <!-- Invoice items table -->

    <table class="table">
        <thead>
        <tr>
            <th>Summary</th>
            <th class="text-right">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orderInfo as $i)
        <tr>
            <td>
                <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                <h5 class="mb-1">{{ $i->product_name }}</h5>
                Men's Pursuit Running Shoes - [x{{ $i->product_order_quantity }}]
            </td>
            <td class="font-weight-bold align-middle text-right text-nowrap">RM {{ number_format($i->product_price / 1) }}.00</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-right border-0 pt-4"><h5>Total: RM {{ number_format($recipientInfo->payment_total / 1) }}.00</h5></td>
        </tr>
    </table>

    <!-- Thank you note -->

    <h5 class="text-center pt-2">
        Thank you for your purchase with us!
    </h5>

</div>

</body>
