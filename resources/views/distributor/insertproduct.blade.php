@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Insert SN Product <i class="fa fa-paper-plane-o"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Product +</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('product.items.store') }}" enctype="multipart/form-data" id="insert-product-form">
                        @csrf

                        <label for="prod_name" class="error"></label>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="test">
                            <label for="prod_name">Product Name</label>
                        </div>
                        <label for="prod_sn" class="error"></label>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="prod_sn" name="prod_sn" placeholder="test">
                            <label for="prod_sn">Product No.</label>
                        </div>
                        <label for="prod_price" class="error"></label>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="prod_price" name="prod_price" placeholder="test">
                            <label for="prod_price">Product Price</label>
                        </div>
                        <label for="prod_link" class="error"></label>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="prod_link" name="prod_link" placeholder="test">
                            <label for="prod_link">Product Link</label>
                        </div>
                        <label for="prod_image" class="error"></label>
                        <div class="form-floating mb-3">
                            <div class="col-sm-auto">
                                <input type="file" name="prod_image" id="prod_image" class="form-control">
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" id="prod_warranty" name="prod_warranty">
                                        <option selected>Please select...</option>
                                        <option value="1">1 year</option>
                                        <option value="2">2 year</option>
                                        <option value="3">3 year</option>
                                        <option value="4">4 year</option>
                                        <option value="5">5 year</option>
                                        <option value="6">6 year</option>
                                        <option value="7">7 year</option>
                                        <option value="8">8 year</option>
                                        <option value="9">9 year</option>
                                        <option value="10">10 year</option>
                                    </select>
                                    <label for="prod_warranty">Warranty Duration</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" name="prod_category" id="prod_category">
                                        <option selected>Please select...</option>
                                        <option value="ACCESSORIES">ACCESSORIES</option>
                                        <option value="AIR COOLER">AIR COOLER</option>
                                        <option value="CASES">CASES</option>
                                        <option value="FANS">FANS</option>
                                        <option value="GAMING CHAIR">GAMING CHAIR</option>
                                        <option value="GAMING DESK">GAMING DESK</option>
                                        <option value="GPU">GPU</option>
                                        <option value="HEADSETS">HEADSETS</option>
                                        <option value="KEYBOARDS">KEYBOARDS</option>
                                        <option value="LIQUID COOLER">LIQUID COOLER</option>
                                        <option value="MONITOR">MONITOR</option>
                                        <option value="MOTHERBOARD">MOTHERBOARD</option>
                                        <option value="MOUSE">MOUSE</option>
                                        <option value="PSU">PSU</option>
                                        <option value="RAM">RAM</option>
                                        <option value="SSD">SSD</option>
                                        <option value="THERMAL PASTE">THERMAL PASTE</option>
                                        <option value="VIRTUAL REALITY">VIRTUAL REALITY</option>
                                    </select>
                                    <label for="prod_category">Product Category</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" name="prod_brand" id="prod_brand">
                                        <option selected>Please select...</option>
                                        <option value="NZXT">NZXT</option>
                                        <option value="FRACTAL DESIGN">FRACTAL DESIGN</option>
                                        <option value="ASUS ROG">ASUS ROG</option>
                                    </select>
                                    <label for="prod_brand">Product Brand</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="prod_stock" name="prod_stock" placeholder="test">
                                        <label for="prod_price">Product Stock</label>
                                    </div>
                                </div>
                                <label for="prod_stock" class="error"></label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end" style="width: 49%">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 49%">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 20px 40px;">
        <div class="card-body">
            @if($fetchProduct->count() == 0)
                <button style="width: 100%" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Add New Product +
                </button>
            @else
                <form>
                    <dl class="row">
                        <dt class="col-sm-3"></dt>
                        <dd class="col-sm-9">
                            <img id="p_picture" width="300" height="300" />
                        </dd>

                        <dt class="col-sm-3">Name</dt>
                        <dd class="col-sm-9" id="p_name"><b>Select product</b></dd>

                        <dt class="col-sm-3">Product ID</dt>
                        <dd class="col-sm-9">
                            <p id="products_id_dist">Select product ID</p>
                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="floatingSelectProduct" aria-label="Floating label select example" style="height: 60px" onchange="myFunctions()">
                                    @foreach($fetchProduct as $prod)
                                        <option value="{{ $prod->product_name }}" selected>{{ $prod->product_name }}</option>
                                    @endforeach
                                </select>
                                <label for="floatingSelectProduct">Select Product</label>
                            </div>
                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="floatingSelectProductID" aria-label="Floating label select example" style="height: 60px">
                                    @foreach($fetchProduct as $prod)
                                        <option value="{{ $prod->product_image_path }}" selected>{{ $prod->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </dd>

                        <dt class="col-sm-3">Serial Number</dt>
                        <dd class="col-sm-9">
                            <div class="form-floating" style="margin-bottom: 10px">
                                <select class="form-select" id="floatingSelectBatch" name="floatingSelectBatch" aria-label="Floating label select example" style="height: 60px">
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                                <label for="floatingSelectBatch">Select Batch</label>
                            </div>
                            <input type="text" class="form-control" id="insert_product_sn" name="insert_product_sn" placeholder="Insert SN Product" value="">
                        </dd>
                    </dl>

                    <div class="row">
                        <div class="col">
                            <button style="width: 100%" type="submit" class="btn btn-primary" id="btn_submit_dist_form" name="btn_submit_dist_form">
                                Insert
                            </button>
                        </div>
                        <div class="col">
                            <button style="width: 100%" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Add New Product +
                            </button>
                        </div>
                    </div>

                </form>
            @endif
        </div>
    </div>

    <div class="card shadow" style="margin-top: 10px">
        <div class="card-body">
            @if($fetchProductJoin->count() > 0)
                <table id="distri-product-table" class="display" style="width:100%">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Serial Number</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fetchProductJoin as $lol)
                        <tr>
                            <td>{{ $lol->product_name }}</td>
                            <td>Batch #{{ $lol->batch_no }}</td>
                            <td>{{ $lol->serial_number }}</td>
                            <td>{{ $lol->status }}</td>
                            <td>{{ date('d-M-Y H:i A', strtotime($lol->created_at)) }}</td>
                            <td>
                                <button type="button" class="btn btn-danger" style="background-color: transparent; border: none; color: black"
                                        onclick="event.preventDefault();
                                            document.getElementById('update-product-status-{{ $lol->id }}').submit()">
                                    @if($lol->status == 'Occupied')
                                        <i data-feather="toggle-right" class="feather-32"></i>
                                    @elseif($lol->status == 'Not Occupied')
                                        <i data-feather="toggle-left" class="feather-32"></i>
                                    @endif
                                </button>

                                <form id="update-product-status-{{ $lol->id }}" action="{{ route('distributor.update-status', $lol->id) }}" method="POST" style="display: none">
                                    @csrf
                                    @method("PATCH")
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>
                    Dear Valued Distributor,
                    Please add product before insert SN.
                </p>
            @endif
        </div>
    </div>

    <script>
        feather.replace();
        myFunctions();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_submit_dist_form").click(function(e){

            e.preventDefault();

            var product_id_dist_form = $("#products_id_dist").text();
            var batch_no = $("#floatingSelectBatch").val();
            var sn = $("#insert_product_sn").val();

            $.ajax({
                type:'POST',
                url:"{{ route('distributor.insertsn.product.dist') }}",
                data:{products_id_dist:product_id_dist_form, floatingSelectBatch:batch_no, insert_product_sn:sn},
                success:function(data){
                    if ( data['success'] )
                        location.reload();
                    else
                        alert('EXISTING.')

                }
            });
        });

        $(document).ready(function() {
            $("#insert-product-form").validate({
                rules: {
                    prod_name : {
                        required: true
                    },
                    prod_sn: {
                        required: true
                    },
                    prod_price: {
                        required: true
                    },
                    prod_link: {
                        required: true
                    },
                    prod_image: {
                        required: true
                    },
                    prod_duration: {
                        required: true
                    },
                    prod_category: {
                        required: true
                    },
                    prod_brand: {
                        required: true
                    },
                    prod_stock: {
                        required: true
                    }
                },
                messages : {
                    prod_name: {
                        required: "Please enter product name"
                    },
                    prod_sn: {
                        required: "Please enter serial number",
                    }
                }
            });

            $('#distri-product-table').DataTable();
        });

        function myFunctions() {
            $('#floatingSelectProductID').attr('hidden', true);

            $("#floatingSelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#floatingSelectProduct option:selected");
            var value2 = $("#floatingSelectProductID option:selected");

            document.getElementById('p_name').innerHTML = value.text().bold();
            document.getElementById('products_id_dist').innerHTML = value2.text();
            document.getElementById("p_picture").src = "/storage/product/" + value2.val();

            $('#floatingSelectProduct').change(function(){
                $("#floatingSelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#floatingSelectProduct option:selected");
                var value2 = $("#floatingSelectProductID option:selected");

                document.getElementById('p_name').innerHTML = value.text().bold();
                document.getElementById('products_id_dist').innerHTML = value2.text();
                document.getElementById("p_picture").src = "/storage/product/" + value2.val();
            });
        }
    </script>
@endsection
