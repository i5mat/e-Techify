@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Your Cart <img src="/image/carts.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="margin-bottom: 10px">
        <div class="card-body">
            <div class="text-center" style="margin-bottom: 10px">
                <img src="/image/location.png">
            </div>

            <dl class="row">
                <dt class="col-sm-3">Name & Phone No.</dt>
                <dd class="col-sm-9" id="user_name"><b>Select recipient</b></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">
                    <p id="address">Select address</p>
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect" aria-label="Floating label select example" style="height: 60px" onchange="myFunction()">
                            <option>-</option>
                            @foreach($userinfo as $ui)
                                <option value="{{ $ui->address }}" selected>{{ $ui->address }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Select Address</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect2" aria-label="Floating label select example" style="height: 60px; margin-top: 10px">
                            <option>-</option>
                            @foreach($userinfo as $ui)
                                <option value="{{ $ui->name }} (+60) {{ $ui->phone_no }}" selected>{{ $ui->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect3" aria-label="Floating label select example" style="height: 60px; margin-top: 10px">
                            <option>-</option>
                            @foreach($userinfo as $ui)
                                <option value="{{ $ui->id }}" selected>{{ $ui->id }}</option>
                            @endforeach
                        </select>
                    </div>
            </dl>
        </div>
    </div>


    <div class="card text-center">
        <div class="card-body">
            <table class="table" style="margin-top: 20px">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col"></th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $i)
                    <form method="POST" action="{{ route('order.purchase.orderdetails', $i->order_id) }}" enctype="multipart/form-data">
                        @csrf
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }}</td>
                        <td><img src="/image/malaysia.png"> <span>{{ $i->product_price }}</span></td>
                        <td>
                            <input type='number' name="{{ $i->product_id }}[]" min="1" value="{{ $i->product_order_quantity }}" class="text-center" data-price="{{ $i->product_price }}" id="qnt_{{ $loop->iteration }}" oninput="CalculateItemsValue()">
                            <input type="number" id="get_qty{{ $loop->iteration }}" name="get_qty{{ $loop->iteration }}" hidden>
                            <input type="text" id="get_prod_id" name="get_prod_id" value="{{ $i->product_id }}" hidden>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info" style="background-color: transparent; border: none">
                                X
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card align-content-center" style="margin-top: 10px">
        <div class="card-body">
            <h2 class="card-title display-6">Summary</h2>
            <h6 class="card-subtitle mb-2 text-muted">Payment Information</h6>
            <table class="table">
                <tbody>
                <tr>
                    <th scope="row">Merchandise Subtotal </th>
                    <td id="merchTotal"></td>
                </tr>
                <tr>
                    <th scope="row">Shipping - XT Express </th>(Included with insurance and extra protection)
                    <td>RM 25.00</td>
                </tr>
                <tr>
                    <th scope="row">Payment Method</th>
                    <td>
                        <i class="fa fa-amazon"></i>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="text-center">
                <p class="lead">
                    Merchandise Subtotal ({{ $items->count() }} items):
                </p>

                <h1 class="display-5" id="ItemsTotal"></h1>
                <input type="number" id="tot" name="tot" hidden>
                <input type="number" id="get_add_id" name="get_add_id" hidden>
                <div>
                    <img class="mr-2"
                         src="/image/visa.png"
                         alt="Visa">
                    <img class="mr-2"
                         src="/image/mastercard.png"
                         alt="Mastercard">
                </div>
                <button type="submit" class="btn btn-primary btn-lg" style="margin-top: 20px" @if($items->count() == 0) disabled @endif id="submit_btn">Check Out</button>
            </div>
        </div>
    </div>
    </form>

    <script>
        CalculateItemsValue();
        myFunction();

        function myFunction() {

            $('#floatingSelect2').attr('hidden', true);
            $('#floatingSelect3').attr('hidden', true);

            $("#floatingSelect2 option").eq($(this).find(':selected').index()).prop('selected',true);
            $("#floatingSelect3 option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#floatingSelect option:selected");
            var value2 = $("#floatingSelect2 option:selected");
            var value3 = $("#floatingSelect3 option:selected");

            document.getElementById('address').innerHTML = value.text();
            document.getElementById('user_name').innerHTML = value2.val().bold();
            document.getElementById('get_add_id').value = value3.val();

            $('#floatingSelect').change(function(){
                $("#floatingSelect2 option").eq($(this).find(':selected').index()).prop('selected',true);
                $("#floatingSelect3 option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#floatingSelect option:selected");
                var value2 = $("#floatingSelect2 option:selected");
                var value3 = $("#floatingSelect3 option:selected");

                document.getElementById('address').innerHTML = value.text();
                document.getElementById('user_name').innerHTML = value2.val().bold();
                document.getElementById('get_add_id').value = value3.val();

                $('#floatingSelect2').attr('disabled', true);
            });
        }

        function CalculateItemsValue() {
            var total = 0, merchantTotal = 0;
            var total_items = {{ $items->count() }};
            for (i = 1; i <= total_items; i++) {

                itemID = document.getElementById("qnt_"+i);
                if (typeof itemID === 'undefined' || itemID === null) {
                    alert("No such item - " + "qnt_"+i);
                } else {
                    total = total + parseInt(itemID.value) * parseInt(itemID.getAttribute("data-price"));
                }

                if (isNaN(total))
                    total = 0;

                var a = document.getElementById("qnt_"+i).value;
                document.getElementById("get_qty"+i).value = a;
            }
            merchantTotal = total;
            total += 25;

            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'MYR',
            });

            document.getElementById('ItemsTotal').innerHTML = formatter.format(total);
            document.getElementById('merchTotal').innerHTML = formatter.format(merchantTotal);
            document.getElementById('tot').value = total;
        }
    </script>
@endsection
