@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <h1 class="display-2 text-center">Insert SN <i class="fa fa-paper-plane"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A view to insert product serial number.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
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
                    <form method="POST" action="{{ route('product.items.store') }}" enctype="multipart/form-data" id="insert-product-form-modal">
                        @csrf

                        <label for="prod_name" class="error"></label>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="test" maxlength="42">
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

    <div class="row" style="margin-top: 10px">
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0" style="font-weight: bold">Product SN</h6>
                </div>
                <div class="card-body border-3 border-bottom border-warning">
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
                                                <option value="{{ $prod->user_id }}" selected>{{ $prod->product_name }}</option>
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
                                            <option value="1" selected>Q1 - Jan, Feb, Mar</option>
                                            <option value="2">Q2 - Apr, May, June</option>
                                            <option value="3">Q3 - Jul, Aug, Sep</option>
                                            <option value="4">Q4 - Oct, Nov, Dec</option>
                                        </select>
                                        <label for="floatingSelectBatch">Select Batch</label>
                                    </div>
                                    <input type="text" class="form-control" id="insert_product_sn" name="insert_product_sn" placeholder="Insert SN Product" value="">
                                </dd>
                            </dl>

                            @can('is-distributor')
                                <div class="row">
                                    <div class="col">
                                        <button style="width: 100%" type="button" class="btn btn-primary" id="btn_submit_dist_form" name="btn_submit_dist_form">
                                            Insert
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button style="width: 100%" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Add New Product +
                                        </button>
                                    </div>
                                </div>
                            @endcan
                            @can('is-reseller')
                                <div class="row">
                                    <div class="col">
                                        <button style="width: 100%" type="button" class="btn btn-primary" id="btn_submit_dist_form" name="btn_submit_dist_form">
                                            Insert
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        </form>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0" style="font-weight: bold">Statistic</h6>
                </div>
                <div class="card-body pt-4 pb-2 border-3 border-bottom border-warning">
                    <div id="graph" style="height: 628px"></div>
                </div>
            </div>
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
                                        <i class="fa fa-toggle-on fa-2x"></i>
                                    @elseif($lol->status == 'Not Occupied')
                                        <i class="fa fa-toggle-off fa-2x"></i>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        feather.replace();
        myFunctions();

        document.addEventListener('DOMContentLoaded', function () {
            var batchQ = @json($getBatchQuarter);

            Highcharts.chart('graph', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Total Batch Counter'
                },
                subtitle: {
                    text: 'Total batch product received for XT Technology'
                },
                credits: false,
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: (function() {
                    var series = [];

                    $.each(batchQ, function(i, item) {
                        const str = item.Q_Batch.split(', ').map(Number);
                        series.push({
                            name: 'Batch ' + item.batch_no,
                            data: str,
                        });
                        console.log(str)
                    })

                    return series;
                }()),
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#btn_submit_dist_form").click(function(e){

            if ($("#insert_product_sn").val() === '') {
                Swal.fire(
                    'Input NULL',
                    'Please input all fields with relevant information',
                    'error'
                )
            } else {
                e.preventDefault();

                var product_id_dist_form = $("#products_id_dist").text();
                var batch_no = $("#floatingSelectBatch").val();
                var sn = $("#insert_product_sn").val();
                var distri_user_id = $("#floatingSelectProduct").val()

                $.ajax({
                    type:'POST',
                    url:"{{ route('distributor.insertsn.product.dist') }}",
                    data:{products_id_dist:product_id_dist_form, floatingSelectBatch:batch_no, insert_product_sn:sn, distri_id:distri_user_id},
                    success:function(data){
                        if ( data['success'] )
                            location.reload();
                        else
                            alert('EXISTING.')

                    }
                });
            }
        });

        $(document).ready(function() {
            $('#distri-product-table').DataTable();

            $("#insert-product-form-modal").validate({
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
        });

        function myFunctions() {
            $('#floatingSelectProductID').attr('hidden', true);

            $("#floatingSelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#floatingSelectProduct option:selected");
            var value2 = $("#floatingSelectProductID option:selected");

            document.getElementById('p_name').innerHTML = value.text().bold();
            document.getElementById('products_id_dist').innerHTML = value2.text();
            document.getElementById("p_picture").src = "{{ \Storage::disk('s3')->url('product/') }}" + value2.val();

            $('#floatingSelectProduct').change(function(){
                $("#floatingSelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#floatingSelectProduct option:selected");
                var value2 = $("#floatingSelectProductID option:selected");

                //alert($("#floatingSelectProduct option:selected").val())

                document.getElementById('p_name').innerHTML = value.text().bold();
                document.getElementById('products_id_dist').innerHTML = value2.text();
                document.getElementById("p_picture").src = "{{ \Storage::disk('s3')->url('product/') }}" + value2.val();
            });
        }
    </script>
@endsection
