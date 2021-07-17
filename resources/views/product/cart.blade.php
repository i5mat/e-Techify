@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <h1 class="display-2 text-center">Cart <img src="/image/carts.png"/></h1>

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
            @if(\App\Models\Address::where('user_id', Auth::id())->exists())
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
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                                    style="height: 60px" onchange="myFunction()">
                                <option disabled>-</option>
                                @foreach($userinfo as $ui)
                                    <option value="{{ $ui->address }}" selected>{{ $ui->address }}</option>
                                @endforeach
                            </select>
                            <label for="floatingSelect">Select Address</label>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect2" aria-label="Floating label select example"
                                    style="height: 60px; margin-top: 10px">
                                <option>-</option>
                                @foreach($userinfo as $ui)
                                    <option value="{{ $ui->name }} (+60) {{ $ui->phone_no }}"
                                            selected>{{ $ui->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect3" aria-label="Floating label select example"
                                    style="height: 60px; margin-top: 10px">
                                <option>-</option>
                                @foreach($userinfo as $ui)
                                    <option value="{{ $ui->id }}" selected>{{ $ui->id }}</option>
                                @endforeach
                            </select>
                        </div>
                </dl>
            @else
                <p>
                    Dear Valued Customer,
                    Please add your address.
                </p>
                <a href="/user/address">
                    <button class="btn btn-warning" style="width: 100%">Address +</button>
                </a>
            @endif
        </div>
    </div>


    <div class="card text-center">
        <div class="card-body">
            @if($items->count() <= 0)
                <div class="row text-center">
                    <img src="/image/no-item.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                    <h1 class="display-6">Cart is empty.</h1>
                </div>
            @else
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
                        <form id="form_cart" name="form_cart">
                            @csrf
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <img src="/storage/product/{{ $i->product_image_path }}"
                                         style="width:120px; height:120px;">
                                </td>
                                <td>{{ $i->product_name }}</td>
                                <td><b>RM</b> <span>{{ $i->product_price }}</span></td>
                                <td>
                                    <input type='number' name="{{ $i->product_id }}[]" min="1" max="{{ $i->product_stock_count }}"
                                           value="{{ $i->product_order_quantity }}" class="text-center form-control"
                                           data-price="{{ $i->product_price }}" id="qnt_{{ $loop->iteration }}"
                                           oninput="CalculateItemsValue(); validity.valid||(value='');">
                                    <input type="number" id="get_qty{{ $loop->iteration }}"
                                           name="get_qty{{ $loop->iteration }}" hidden>
                                    <input type="text" id="get_prod_id" name="get_prod_id" value="{{ $i->product_id }}"
                                           hidden>
                                    <input type="text" id="get_ord_id" name="get_ord_id" value="{{ $i->order_id }}" hidden>
                                </td>
                                <td>
                                    <button class="btn btn-danger" style="background-color: transparent; border: none"
                                            id="btn_del_item{{ $loop->iteration }}" data-id="{{ $i->o_d_id }}">
                                        <img src="/image/delete.png">
                                    </button>
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="card align-content-center" style="margin-top: 10px">
        <div class="card-body">
            <h2 class="card-title display-6">Summary</h2>
            <h6 class="card-subtitle mb-2 text-muted">Payment Information</h6>
            <table class="table">
                <tbody>
                <tr>
                    <th scope="row">Merchandise Subtotal</th>
                    <td id="merchTotal"></td>
                </tr>
                <tr>
                    <th scope="row">Shipping - XT Express</th>
                    (Included with insurance and extra protection)
                    <td>RM 25.00</td>
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
                <div id="paypal-payment-button" style="margin-top: 15px"></div>
            </div>
        </div>
    </div>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AYGoN9I1BkU083SCTYJyibLNCCNE0eqXG6BLDbRSSKINBiWCK7eSdOKOXFeb4vz9RuG3tcMo9oYQl7R4&disable-funding=credit,card&currency=MYR"></script>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.serializeJSON/3.2.1/jquery.serializejson.min.js"></script>
    <script>

        CalculateItemsValue();
        myFunction();

        var myA = @json($myArrays);
        var recipient = @json($userinfo);
        console.log(myA);
        console.log(recipient[0].address.toUpperCase());
        console.log(recipient)

        for (z = 1; z <= myA.length; z++) {
            $("#btn_del_item"+z).click(function () {
                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");

                $.ajax(
                    {
                        url: "http://127.0.0.1:8000/product/del-item-cart/" + id,
                        type: 'DELETE',
                        data: {
                            "id": id,
                            "_token": token,
                        },
                        success: function () {
                            location.reload();
                        }
                    });
            });
        }

        function myFunction() {

            $('#floatingSelect2').attr('hidden', true);
            $('#floatingSelect3').attr('hidden', true);

            $("#floatingSelect2 option").eq($(this).find(':selected').index()).prop('selected', true);
            $("#floatingSelect3 option").eq($(this).find(':selected').index()).prop('selected', true);
            var value = $("#floatingSelect option:selected");
            var value2 = $("#floatingSelect2 option:selected");
            var value3 = $("#floatingSelect3 option:selected");

            document.getElementById('address').innerHTML = value.text();
            document.getElementById('user_name').innerHTML = value2.val().bold();
            document.getElementById('get_add_id').value = value3.val();

            $('#floatingSelect').change(function () {
                $("#floatingSelect2 option").eq($(this).find(':selected').index()).prop('selected', true);
                $("#floatingSelect3 option").eq($(this).find(':selected').index()).prop('selected', true);
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

                itemID = document.getElementById("qnt_" + i);
                if (typeof itemID === 'undefined' || itemID === null) {
                    alert("No such item - " + "qnt_" + i);
                } else {
                    total = total + parseInt(itemID.value) * parseInt(itemID.getAttribute("data-price"));
                }

                if (isNaN(total))
                    total = 0;

                var a = document.getElementById("qnt_" + i).value;
                document.getElementById("get_qty" + i).value = a;

                var x = document.getElementById("qnt_" + i).max;

                if ($('#qnt_'+i).val() > parseInt(x)) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Stock is around '+ parseInt(x)+ ' only',
                        icon: 'error',
                        confirmButtonText: 'Okay',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            document.getElementById('ItemsTotal').innerHTML = 'Stock is either empty or not available 😟';
                            document.getElementById('merchTotal').innerHTML = 'Please re-enter quantity properly 🙏'
                            $('#paypal-payment-button').attr('hidden', true);
                        }
                    })
                    $('#qnt_'+i).val(1)
                }
            }
            if(total_items == 0)
                $('#paypal-payment-button').attr('hidden', true);
            else
                $('#paypal-payment-button').attr('hidden', false);

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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'pill'
            },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            currency_code: 'MYR',
                            value: document.getElementById('tot').value
                        }
                    }]
                })
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    //alert('Transaction completed by ' + details.payer.name.given_name);
                    console.log(details)

                    var pay_total = details.purchase_units[0].amount.value;
                    var pay_method = details.purchase_units[0].soft_descriptor;
                    var order_Id = $("input[name=get_ord_id]").val();
                    var name = '{{ env('APP_URL') }}';

                    Swal.fire({
                        icon: 'success',
                        title: 'Processing your request, waiting for PayPal and arranging your orders!',
                        didOpen: function () {
                            Swal.showLoading()
                            $.ajax({
                                type: 'GET',
                                url: name+"/order/purchase/success/" + order_Id,
                                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                                data: $("#form_cart").serialize() + '&pay_method=' + pay_method + '&pay_total=' + pay_total,
                                success: function (data) {
                                    console.log(data)
                                    if (data['success']) {

                                        var msg = 'Dear, ' + recipient[0].name.toUpperCase() + '. Thank you for shopping with us at e-Techify! Your order ID is ' + myA[0].order_id + " and it will be sent to you in 3-7 working days.";
                                        var phone = recipient[0].phone_no;
                                        fetch("https://terminal.adasms.com/api/v1/send?_token=Zk24gMmtAqWl1QJbIBwp3biEtZPp10bo&phone=60"+ phone +"&message=" + msg, {
                                            "method": "POST"
                                        })
                                            .then(response => console.log(response))
                                            .catch(err => console.error(err));

                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Transaction Success',
                                            text: 'Transaction completed by ' + details.purchase_units[0].shipping.name.full_name,
                                            confirmButtonText: `Click me!`,
                                        }).then((result) => {
                                            /* Read more about isConfirmed, isDenied below haha */
                                            if (result.isConfirmed) {
                                                window.location.replace(name+"/order/success/thank-you");
                                            }
                                        })
                                    } else
                                        alert('EXISTING.')

                                }
                            });
                        }
                    })
                })
            },
            onCancel: function (data) {
                Swal.fire(
                    'Transaction Failed',
                    'Failed, or canceled. Could be insufficient funds. Please check your balance.',
                    'error'
                );
            }
        }).render('#paypal-payment-button');
    </script>
@endsection
