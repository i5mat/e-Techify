@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Orders! <img src="/image/received.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">To Pay</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">To Ship</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">To Receive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Completed</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table text-center" style="margin-top: 20px">
                        <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col"></th>
                            <th scope="col">Unit Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items_to_pay as $i)
                            <tr>
                                <td>
                                    <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                                </td>
                                <td>{{ $i->product_name }}</td>
                                <td><img src="/image/malaysia.png"> <span>{{ $i->product_price }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <p class="lead">
                            Order total
                        </p>
                        <h1 class="display-5">
                            RM 1.00
                        </h1>
                    </div>

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <table class="table text-center" style="margin-top: 20px">
                        <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col"></th>
                            <th scope="col">Unit Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items_to_ship as $i)
                            <tr>
                                <td>
                                    <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                                </td>
                                <td>{{ $i->product_name }}</td>
                                <td><img src="/image/malaysia.png"> <span>{{ $i->product_price }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        <p class="lead">
                            Order total
                        </p>
                        <h1 class="display-5">
                            RM 1.00
                        </h1>
                    </div>
                </div>
                <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">DOLOR</div>
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">SIT AMET</div>
            </div>
        </div>
    </div>

    <script>
        $('#myTab a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        })
    </script>

@endsection
