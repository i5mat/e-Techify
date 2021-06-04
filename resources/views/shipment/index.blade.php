@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Shipment <i class="fa fa-cubes"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    @can('is-reseller')

        <div class="row mb-2">
            <div class="col">
                    @if($getItems->count() > 0)
                        <div class="card mb-2">
                            <div class="card-header">
                                Cart
                            </div>
                            <div class="card-body text-center border-3 border-bottom border-warning">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Unit Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($getItems as $getShipment)
                                        <tr>
                                            <td>
                                                <img src="/storage/product/{{ $getShipment->product_image_path }}"
                                                     style="width:120px; height:120px;">
                                            </td>
                                            <td>{{ $getShipment->product_name }}</td>
                                            <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                                            <td><img src="/image/malaysia.png"> <span>{{ $getShipment->product_price }}</span></td>
                                            <td>x{{ $getShipment->product_order_quantity }}</td>
                                            <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->product_price * $getShipment->product_order_quantity }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <p class="lead">
                                        Merchandise Subtotal ({{ $getItems->count() }} items):
                                    </p>

                                    <h1 class="display-5" id="total"></h1>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2" style="width: 100%" id="btn_request_shipment">Request Shipment</button>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                Cart
                            </div>
                            <div class="card-body border-3 border-bottom border-warning">
                                <h1 class="display-6 text-center">Your cart is empty.</h1>
                            </div>
                        </div>
                    @endif
            </div>
            <div class="col-6 col-sm-5">
                <div class="card">
                    <div class="card-header">
                        Request
                    </div>
                    <div class="card-body border-3 border-bottom border-warning">
                        <div class="col text-center">
                            <img id="product_pic" width="300" height="300" />

                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="SelectProductID" aria-label="Floating label select example" style="height: 60px">
                                    <option value=""><strong>Product ID</strong></option>
                                    @foreach($fetchProduct as $prod)
                                        <option value="{{ $prod->product_image_path }}" selected>{{ $prod->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-2">
                                <select class="form-select" id="product_brand" name="product_brand">
                                    <option value=""><strong>Name</strong></option>
                                    @foreach($getProductBrand as $prod_brand)
                                        <option value="{{ $prod_brand->product_brand }}">{{ $prod_brand->product_brand }}</option>
                                    @endforeach
                                </select>
                                <label for="prod_brand">Brand</label>
                            </div>
                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="SelectProduct" aria-label="Floating label select example" style="height: 60px" onchange="myFunctions()">
                                    <option value=""><strong>Products</strong></option>
                                </select>
                                <label for="floatingSelectProduct">Select Product</label>
                            </div>
                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="SelectQuantity" aria-label="Floating label select example" style="height: 60px">
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                <label for="floatingSelectProduct">Select Product Quantity</label>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="Leave a comment here" id="remarkTextArea" style="height: 100px"></textarea>
                                <label for="remarkTextArea">Remark</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2" style="width: 100%" id="btn_add_shipment">Add To List</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Status
            </div>
            <div class="card-body border-3 border-bottom border-warning">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Shipped <span class="badge bg-secondary ms-2" style="color: white">{{ $shipped->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($shipped as $i)
                                        <div @if($shipped->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-primary" style="border: none">
                                                <div class="text-right badge bg-primary"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-primary" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Waiting Approval <span class="badge bg-secondary ms-2" style="color: white">{{ $waitingApproval->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($waitingApproval as $i)
                                        <div @if($waitingApproval->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-warning" style="border: none">
                                                <div class="text-right badge bg-warning"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-warning" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Approved <span class="badge bg-secondary ms-2" style="color: white">{{ $approved->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($approved as $i)
                                        <div @if($approved->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-success" style="border: none">
                                                <div class="text-right badge bg-success"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-success" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFourth">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourth" aria-expanded="false" aria-controls="collapseFourth">
                                Requested <span class="badge bg-secondary ms-2" style="color: white">{{ $requested->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseFourth" class="accordion-collapse collapse" aria-labelledby="headingFourth" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($requested as $i)
                                        <div @if($requested->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-dark" style="border: none">
                                                <div class="text-right badge bg-dark"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-dark" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endcan

    @can('is-distributor')
        @if($getItems->count() > 0)

        <div class="card">
            <div class="card-body">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Shipped <span class="badge bg-secondary ms-2" style="color: white">{{ $shipped->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($shipped as $i)
                                        <div @if($shipped->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-primary" style="border: none">
                                                <div class="text-right badge bg-primary"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-primary" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Waiting Approval <span class="badge bg-secondary ms-2" style="color: white">{{ $waitingApproval->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($waitingApproval as $i)
                                        <div @if($waitingApproval->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-warning" style="border: none">
                                                <div class="text-right badge bg-warning"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-warning" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Approved <span class="badge bg-secondary ms-2" style="color: white">{{ $approved->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($approved as $i)
                                        <div @if($approved->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-success" style="border: none">
                                                <div class="text-right badge bg-success"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-success" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFourth">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFourth" aria-expanded="false" aria-controls="collapseFourth">
                                Requested <span class="badge bg-secondary ms-2" style="color: white">{{ $requested->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapseFourth" class="accordion-collapse collapse" aria-labelledby="headingFourth" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row g-1">
                                    @foreach($requested as $i)
                                        <div @if($requested->count() == 1) class="col-md-12" @else class="col-md-6" @endif>
                                            <div class="card p-2 border-5 border-bottom border-dark" style="border: none">
                                                <div class="text-right badge bg-dark"><small class="lead" style="color: white">Request ID
                                                        #{{ $i->id }}</small></div>
                                                <div class="text-center mt-2 p-3"><img src="/image/XT-logo.png" width="100" height="65"/>
                                                    <span class="d-block font-weight-bold">
                                                        <span class="badge bg-dark" style="color: white">{{ $i->status }}
                                                        </span>
                                                    </span>
                                                    <hr>
                                                    <span>Xmiryna Tech</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <i class="fa fa-map-marker"></i>
                                                        <small class="mx-1">Kuala Lumpur, TX</small>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="#" target="_blank"><i data-feather="file-text"></i></a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="card">
                <div class="card-header">
                    Request
                </div>
                <div class="card-body border-3 border-bottom border-warning">
                    <h1 class="display-6 text-center">Your request is empty.</h1>
                </div>
            </div>
        @endif
    @endcan

    <script>
        feather.replace();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @can('is-reseller')
        myFunctions();

        var total_items = {{ $getItems->count() }};
        var merchandise_total = 0;

        for (i = 1; i <= total_items; i++) {
            var formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'MYR',
            });
            var total_price_item = $("#total_shipment_item"+ i).text();

            merchandise_total += parseInt(total_price_item);

            document.getElementById('total').innerHTML = formatter.format(merchandise_total);
            document.getElementById('total_shipment_item'+ i).innerHTML = formatter.format(total_price_item);
        }

        var myArr = @json($retrieveVal);
        console.log(myArr)

        $("#product_brand").change(function(){
            var company = $(this).val();
            var options =  '<option value=""><strong>Products</strong></option>';
            var options2 =  '<option value=""><strong>Product ID</strong></option>';
            $(myArr).each(function(index, value){
                if(value.product_brand == company){
                    options += '<option value="'+value.id+'">'+value.product_name+'</option>';
                    options2 += '<option value="'+value.product_image_path+'">'+value.id+'</option>';
                }
            });

            $('#SelectProduct').html(options);
            $('#SelectProductID').html(options2);
        });

        $("#btn_add_shipment").click(function(e){

            e.preventDefault();

            var user_id = {{ Auth::id() }};
            var product_id = $("#SelectProduct").val();
            var product_qty = $("#SelectQuantity").val();
            var product_remark = $("#remarkTextArea").val();

            console.log(user_id)
            console.log(parseInt(product_id))
            console.log(parseInt(product_qty))
            console.log(product_remark)

            $.ajax({
                type:'POST',
                url:"http://127.0.0.1:8000/shipment/new-shipment-request/" + parseInt(product_id),
                data:{
                    user_id:user_id,
                    prod_id:parseInt(product_id),
                    prod_qty:parseInt(product_qty),
                    prod_remark:product_remark
                },
                success:function(data){
                    if ( data['success'] )
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        $("#btn_request_shipment").click(function(e){

            e.preventDefault();

            var shipment_id = $("#get_shipment_id").text();
            var status = 'Waiting Approval';
            var update_remark = 'Waiting Approval from distributor. If approved please make payment.';

            console.log(parseInt(shipment_id))
            console.log(status)

            $.ajax({
                type:'PATCH',
                url:"http://127.0.0.1:8000/shipment/shipment-request/" + parseInt(shipment_id),
                data:{
                    id:parseInt(shipment_id),
                    status:status,
                    remark:update_remark
                },
                success:function(data){
                    if ( data['success'] )
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        function myFunctions() {
            $('#SelectProductID').attr('hidden', true);

            $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#SelectProduct option:selected");
            var value2 = $("#SelectProductID option:selected");

            document.getElementById("product_pic").src = "/storage/product/" + value2.val();

            $('#SelectProduct').change(function(){
                $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#SelectProduct option:selected");
                var value2 = $("#SelectProductID option:selected");

                document.getElementById("product_pic").src = "/storage/product/" + value2.val();
            });
        }
        @endcan
    </script>
@endsection
