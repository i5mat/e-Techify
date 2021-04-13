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
                            <option selected>-</option>
                            @foreach($userinfo as $ui)
                                <option value="{{ $ui->address }}" >{{ $ui->address }}</option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Select Address</label>
                    </div>
                    <div class="form-floating">
                        <select class="form-select" id="floatingSelect2" aria-label="Floating label select example" style="height: 60px; margin-top: 10px">
                            <option selected>-</option>
                            @foreach($userinfo as $ui)
                                <option value="{{ $ui->name }} (+60) {{ $ui->phone_no }}" >{{ $ui->name }}</option>
                            @endforeach
                        </select>
                    </div>
            </dl>

{{--            <div class="text-center">--}}
{{--                <button id="submit_btn" type="submit" class="btn btn-warning">Change</button>--}}
{{--            </div>--}}

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
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }}</td>
                        <td><img src="/image/malaysia.png"> <span>{{ $i->product_price }}</span></td>
                        <td>
                            <input type='number' min="1" value="1" class="text-center" data-price="{{ $i->product_price }}" id="qnt_{{ $loop->iteration }}" onkeyup="CalculateItemsValue()">
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

    <div class="card" style="margin-top: 10px">
        <div class="card-body text-center">
            <p class="lead">
                Merchandise Subtotal ({{ $items->count() }} items):
            </p>
            <h1 class="display-5" id="ItemsTotal"></h1>
            <div>
                <img class="mr-2" width="60px"
                     src="https://bit.ly/326S7z6"
                     alt="Visa">
                <img class="mr-2" width="60px"
                     src="https://bit.ly/3fYjx2m"
                     alt="American Express">
                <img class="mr-2" width="60px"
                     src="https://bit.ly/3uJVxo3"
                     alt="Mastercard">
            </div>
            <button type="button" class="btn btn-primary btn-lg" style="margin-top: 20px">Check Out</button>
        </div>
    </div>

    <script>
        CalculateItemsValue();
        myFunction();

        function myFunction() {

            $('#floatingSelect2').attr('hidden', true);
            $('#floatingSelect').change(function(){
                $("#floatingSelect2 option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#floatingSelect option:selected");
                var value2 = $("#floatingSelect2 option:selected");

                document.getElementById('address').innerHTML = value.text();
                document.getElementById('user_name').innerHTML = value2.val().bold();

                $('#floatingSelect2').attr('disabled', true);
            });
        }

        function CalculateItemsValue() {
            var total = 0;
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

            }

            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'MYR',
            });

            document.getElementById('ItemsTotal').innerHTML = formatter.format(total);
        }
    </script>
@endsection
