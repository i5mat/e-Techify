@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <h1 class="display-2 text-center">Shipment <i class="fa fa-cubes"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Request desired item here, arrange a shipment request.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    @can('is-reseller')

        <div class="row mb-2">
            <div class="col">
                    @if($getItems->count() > 0)
                        <div class="card mb-2">
                            <div class="card-header" style="font-weight: bold">
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
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($getItems as $getShipment)
                                        <tr>
                                            <td>
                                                <img src="{{ \Storage::disk('s3')->url('product/'.$getShipment->product_image_path) }}"
                                                     style="width:120px; height:120px;">
                                            </td>
                                            <td>{{ $getShipment->product_name }}</td>
                                            <td hidden id="get_shipment_id">{{ $getShipment->shipment_id }}</td>
                                            <td><b>RM</b> <span>{{ $getShipment->new_product_price }}</span></td>
                                            <td>x{{ $getShipment->product_order_quantity }}</td>
                                            <td id="total_shipment_item{{ $loop->iteration }}">{{ $getShipment->new_product_price * $getShipment->product_order_quantity }}</td>
                                            <td>
                                                <button class="btn btn-danger" style="background-color: transparent; border: none"
                                                        id="btn_remove_shipment_item{{ $loop->iteration }}" data-id="{{ $getShipment->shipment_details_id }}">
                                                    <img src="/image/delete.png">
                                                </button>
                                            </td>
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
                                <button type="button" class="btn btn-primary mt-2" style="width: 100%" id="btn_request_shipment">Request Shipment</button>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header" style="font-weight: bold">
                                Cart
                            </div>
                            <div class="card-body border-3 border-bottom border-warning">
                                <img src="/image/no-item.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                                <h1 class="display-6 text-center">Your cart is empty.</h1>
                            </div>
                        </div>
                    @endif
            </div>
            <div class="col-6 col-sm-4 mb-2">
                <div class="card">
                    <div class="card-header" style="font-weight: bold">
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
                                <select class="form-select" id="brand_dist" name="brand_dist">
                                    <option value=""><strong>Name</strong></option>
                                    @if(count($validateShipment) == 0)
                                        @foreach($getDistributor as $brand_distributor)
                                            <option value="{{ $brand_distributor->name }}">{{ $brand_distributor->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($validateShipment as $brand_distributor)
                                            <option value="{{ $brand_distributor->name }}">{{ $brand_distributor->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label for="brand_dist">Brand Distributor</label>
                            </div>
                            <div class="form-floating mb-2">
                                <select class="form-select" id="product_brand" name="product_brand">
                                    <option value=""><strong>Brand</strong></option>
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
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                <label for="floatingSelectProduct">Select Product Quantity</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-2" style="width: 100%" id="btn_add_shipment">Add To List</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" style="font-weight: bold">
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
                                                    <span>Shipment has been shipped, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment is waiting for approval, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment have been approved, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment is in request status, waiting for confirmation 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment has been shipped, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment is waiting for approval, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment have been approved, 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                                                    <span>Shipment is in request status, waiting for confirmation 💥</span>
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <b>{{ date('jS F Y h:i A', strtotime($i->created_at)) }}</b>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-3">
                                                        <span>
                                                            <a href="{{ route('shipment.details', $i->id) }}"><i data-feather="eye"></i></a>
                                                        </span>
                                                        <span>
                                                            <a href="{{ route('shipment.sheet', $i->id) }}" target="_blank"><i data-feather="file-text"></i></a>
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
                <div class="card-header" style="font-weight: bold">
                    Request
                </div>
                <div class="card-body border-3 border-bottom border-warning">
                    <img src="/image/no-shipment.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                    <h1 class="display-6 text-center">Your shipment request is empty.</h1>
                </div>
            </div>
        @endif
    @endcan

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @can('is-reseller')
        myFunctions();

        var myArr = @json($getItems);
        //console.log(myArr);
        console.log(@json($validateShipment));

        for (z = 1; z <= myArr.length; z++) {
            $("#btn_remove_shipment_item"+z).click(function () {
                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");
                var name = '{{ env('APP_URL') }}';

                $.ajax(
                    {
                        url: name+"/shipment/remove-item-request/" + id,
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
        var brandArr = @json($getProductBrand);
        console.log(myArr)
        console.log(brandArr)

        $("#brand_dist").change(function(){
            var company = $(this).val();
            var brand_options = '<option value=""><strong>Brand</strong></option>';
            var product_options = '<option value=""><strong>Product</strong></option>';
            $(brandArr).each(function(index, value){
                if(value.name == company){
                    brand_options += '<option value="'+value.product_brand+'">'+value.product_brand+'</option>';
                }
            });

            $('#SelectProduct').html(product_options);
            $('#product_brand').html(brand_options);
        });

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

            if ($("#brand_dist :selected").text() === 'Name' || $("#product_brand :selected").text() === 'Brand' || $("#SelectProduct :selected").text() === 'Products') {
                Swal.fire(
                    'Null Value',
                    'Please select input brand and product',
                    'error'
                )
            } else {
                e.preventDefault();

                var user_id = {{ Auth::id() }};
                var product_id = $("#SelectProduct").val();
                var product_qty = $("#SelectQuantity").val();
                var product_remark = 'Please process my request.';
                var name = '{{ env('APP_URL') }}';

                console.log(user_id)
                console.log(parseInt(product_id))
                console.log(parseInt(product_qty))
                console.log(product_remark)

                $.ajax({
                    type:'POST',
                    url: name+"/shipment/new-shipment-request/" + parseInt(product_id),
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
            }
        });

        $("#btn_request_shipment").click(function(e){

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    e.preventDefault();

                    var shipment_id = $("#get_shipment_id").text();
                    var status = 'Waiting Approval';
                    var update_remark = 'Waiting Approval from distributor. If approved please make payment.';
                    var name = '{{ env('APP_URL') }}';

                    console.log(parseInt(shipment_id))
                    console.log(status)

                    $.ajax({
                        type:'PATCH',
                        url:name+"/shipment/shipment-request/" + parseInt(shipment_id),
                        data:{
                            id:parseInt(shipment_id),
                            status:status,
                            remark:update_remark
                        },
                        success:function(data){
                            if ( data['success'] ) {
                                Swal.fire({
                                    title: 'Submitted!',
                                    text: "Your request has been submitted.",
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                })
                            }
                            else
                                alert('EXISTING.')

                        }
                    });
                }
            })
        });

        function myFunctions() {
            $('#SelectProductID').attr('hidden', true);

            $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#SelectProduct option:selected");
            var value2 = $("#SelectProductID option:selected");

            document.getElementById("product_pic").src = "/image/no-shipment.png";

            $('#SelectProduct').change(function(){
                $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#SelectProduct option:selected");
                var value2 = $("#SelectProductID option:selected");

                document.getElementById("product_pic").src = "{{ \Storage::disk('s3')->url('product/') }}" + value2.val();
            });
        }
        @endcan
    </script>
@endsection
