<!DOCTYPE html>
<html lang="en">
<head>
    <title>RMA Job Sheet</title>
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

                        @can('is-distributor')
                            <h2 class="mb-1">{{ $distriAddress->name }}</h2>
                            {!! wordwrap($distriAddress->address, 40, "<br>\n") !!} <br/><br/>
                            <b>+(60) {{ $distriAddress->phone_no }}</b><br>
                            exclusively by <strong>xmiryna.com.my</strong>
                        @endcan
                        @can ('is-reseller')
                            <h2 class="mb-1">Xmiryna Technology [SA0546866-M]</h2>
                            No. 79 Jalan Taman Melati 1,<br>
                            Taman Melati, Setapak,<br>
                            53100, Kuala Lumpur<br>
                            xmiryna.tech@outlook.com / <b>+(60) 17-217 8319</b><br>
                            <strong>xmiryna.com.my</strong>
                        @endcan
                        @can ('is-user')
                            <h2 class="mb-1">{{ $userAddress->name }}</h2>
                            {!! wordwrap($userAddress->address, 40, "<br>\n") !!} <br/><br/>
                            <b>+(60) {{ $userAddress->phone_no }}</b><br><br>
                            exclusively by <h2>Xmiryna Technology</h2>
                        @endcan
                    </div>

                    <div class="col text-center text-md-right">

                        <!-- Dont' display Bill To on mobile -->
                        <span class="d-none d-md-block">
                            <h1>RMA Request</h1>
                            <h2>#{{ $recipientInfo->id }}</h2>
                        </span>

                        <h4 class="mb-0">{{ $recipientInfo->name }}</h4>

                        {!! wordwrap($recipientInfo->address, 40, "<br>\n") !!} <br/><br/>

                        <h5 class="mb-0 mt-3">+(60){{ $recipientInfo->phone_no }}</h5><br>
                        Requested On
                        <h2>{{ date('d M Y', strtotime($recipientInfo->created_at)) }}</h2>
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
            <th class="text-center">Brand</th>
            <th class="text-right">Product SKU</th>
            <th class="text-right">Serial Number</th>
            <th class="text-right">Date Of Purchase</th>
        </tr>
        </thead>
        <tbody>
        @foreach($rmaInfo as $i)
        <tr>
            <td>
                <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                <h5 class="mb-1">{{ $i->product_name }}</h5>
                {{ $i->product_warranty_duration }} years local distributor warranty.
            </td>
            <td class="font-weight-bold align-middle text-center text-nowrap">{{ $i->product_brand }}</td>
            <td class="font-weight-bold align-middle text-right text-nowrap">{{ $i->product_sn }}</td>
            <td class="font-weight-bold align-middle text-right text-nowrap">{{ $i->sn_no }}</td>
            <td class="font-weight-bold align-middle text-right text-nowrap">{{ $i->date_of_purchase }}</td>
        </tr>
        <tr>
            <td colspan="5" class="text-right border-0 pt-4">
                Reason<h2>{{ $i->reason }}</h2>
            </td>
        </tr>
        @endforeach
    </table>

    <!-- Thank you note -->

    <h5 class="text-center pt-2">
        Please print this and include inside your parcel. Thank You!
    </h5>

</div>

</body>
