@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <h1 class="display-2 text-center">Insert Serial Number <img src="/image/sn.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="margin-bottom: 10px">
        <div class="card-body" >
            <dl class="row">
                <dt class="col-sm-3">Name & Phone No.</dt>
                <dd class="col-sm-9" id="user_name"><b>{{ $recipientInfo->name }} +(60) {{ $recipientInfo->phone_no }}</b></dd>

                <dt class="col-sm-3">Address</dt>
                <dd class="col-sm-9">
                    <p id="address">{{ $recipientInfo->address }}</p>

                <dt class="col-sm-3">Tracking No.</dt>
                <dd class="col-sm-9">{{ $recipientInfo->tracking_num }} <span class="badge bg-warning text-dark">XT Express</span></dd>

                <dt class="col-sm-3">Tracking Status</dt>
                <dd class="col-sm-9 fw-bold">{{ $recipientInfo->order_status }}</dd>

                <dt class="col-sm-3">Barcode</dt>
                <dd class="col-sm-9">{!! DNS1D::getBarcodeSVG($recipientInfo->tracking_num, "C39", 1, 50, '#2A3239') !!} </dd>

                <dt class="col-sm-3">QR Code</dt>
                <dd class="col-sm-9">{!! DNS2D::getBarcodeHTML($recipientInfo->tracking_num, 'QRCODE', 5, 5) !!} </dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($findSN->count() > 0)
            <dl class="row">
                <dt class="col-sm-3">Suggested SN</dt>
                <dd class="col-sm-9">
                    @foreach($findSN as $z)
                        <div class="lead" id="prod_name_sn">{{ $z->product_name }}</div>
                        @foreach(explode(', ', $z->sn_no) as $info)
                            <div id="draggable{{ $loop->index }}{{ $z->id }}" class="btn btn-primary" style="margin-top: 5px; margin-bottom: 5px">{{ $info }}</div>
                        @endforeach
                    @endforeach
                </dd>
            </dl>
            @else
                <dl class="row">
                    <dt class="col-sm-3">Suggested SN</dt>
                    <dd class="col-sm-9">
                        There is no SN that is available, please request distributor to insert new SN.
                    </dd>
                </dl>
            @endif
            <table class="table text-center">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product</th>
                    <th scope="col"></th>
                    <th scope="col">Insert SN</th>
                    <th scope="col">Last Updated</th>
                </tr>
                </thead>
                <tbody class="table">
                @foreach($orderInfo as $i)
                    <form method="POST" action="{{ route('order.purchase.updatesn', $i->order_id) }}" id="sn_insert_form">
                        @csrf
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <img src="/storage/product/{{ $i->product_image_path }}" style="width:120px; height:120px;">
                        </td>
                        <td>{{ $i->product_name }} <p class="lead">[x{{ $i->product_order_quantity }}]</p></td>
                        <td>
                            <input type="text" id="getproduct_qty" name="getproduct_qty" value="{{ $i->product_order_quantity }}" hidden>
                            @for ($x = 0; $x < $i->product_order_quantity; $x++)
                                <input readonly required type="text" class="form-control" id="droppable{{ $x }}{{ $i->product_id }}" name="{{ $i->product_id }}[]" style="margin-top: 5px; margin-bottom: 5px" value="">
                            @endfor
                            <input type="text" id="getproduct_sn" name="getproduct_sn" value="{{ $i->product_id }}" hidden>

                        </td>
                        <td>
                            {{ date('Y-m-d H:i A', strtotime($i->updated_at)) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                <button id="btn_sbmt_sn_no" type="button" class="btn btn-primary" style="width: 100%">Submit</button>
            </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var a = document.getElementById('getproduct_qty').value;
        var b = document.getElementById('getproduct_sn').value;
        var myA = @json($myArrays);
        var myOI = @json($orderInfoArr);
        var counterSN = @json($countTotal);

        console.log(myA);
        console.log(myOI);
        console.log(parseInt(counterSN))
        // console.log(myOI.unshift(0));
        // console.log(myOI);

        $.each(myOI, function(i, item) {
            var lols = myOI[i].product_order_quantity;
            for (z = 0; z < lols; z++) {
                const str = myOI[i].serial_number;

                if (str === null)
                {
                    $("#draggable"+z+myOI[i].product_id).css("font-weight", "bold");
                    $("#draggable"+z+myOI[i].product_id).addClass('btn-warning').removeClass('btn-primary');

                    if ($('#droppable'+z+myOI[i].product_id).val() === '')
                        $('#btn_sbmt_sn_no').attr('disabled', true);

                    console.log('EMPTY SERIAL_NUMBER.')
                    $("#droppable"+z+myOI[i].product_id).droppable({
                        accept: "#draggable"+z+myOI[i].product_id,
                        hoverClass: 'active',
                        drop: function(event, ui) {
                            this.value = $(ui.draggable).text();
                            $(ui.draggable).hide();
                            $('#btn_sbmt_sn_no').attr('disabled', false);
                            var draggableId = ui.draggable.attr("id");
                            var droppableId = $(this).attr("id");
                            console.log(draggableId + '' + droppableId)
                        }
                    });

                    $("#draggable"+z+myOI[i].product_id).draggable({
                        revert: true,
                        helper: 'clone',
                        start: function(event, ui) {
                            $(this).fadeTo('fast', 0.5);
                            console.log('DRAGGED' + $(this).attr("id"))
                            //$("#droppable"+z+myOI[i].product_id).focus();
                            //$(this).css("background-color", "green");
                        },
                        stop: function(event, ui) {
                            //$(this).fadeTo('fast', 0.5);
                            $(this).fadeTo(0, 1);
                            //$(this).draggable('disable');
                            //$(this).css("background-color", "red");
                            //$(this).css("cursor", "default");
                        }
                    });

                    // $("#draggable"+z+myOI[i].product_id).on('mousedown', function(event) {
                    //     // event.preventDefault(); // drag does not work anymore
                    //     console.log('DRAGGED' + $(this).attr("id"))
                    //     setTimeout(function(){
                    //         $("input").focus();
                    //     }, 1);
                    // });

                    $("#btn_sbmt_sn_no").click(function() {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Serial number is inserted accordingly',
                            icon: 'success',
                            confirmButtonText: 'Okay',
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {
                                $("#sn_insert_form").submit();
                            }
                        })
                    });
                }
                else
                {
                    const sn_num = str.split(', ');
                    $('#droppable'+z+myOI[i].product_id).val(sn_num[z])

                    $("#btn_sbmt_sn_no").click(function() {
                        Swal.fire(
                            'Error!',
                            'Input is the same',
                            'error'
                        )
                    });
                }
            }
        });
    </script>
@endsection
