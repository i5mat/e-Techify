@extends('templates.main')

@section('content')
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
                <dd class="col-sm-9">{{ $recipientInfo->order_status }}</dd>

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
                        <div class="draggable btn btn-primary" style="margin-top: 5px; margin-bottom: 5px">{{ $z->serial_number }}</div>
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
                    <form method="POST" action="{{ route('order.purchase.updatesn', $i->order_id) }}">
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
                <p class="lead">
                    <button id="btn_sbmt_sn_no" type="submit" class="btn btn-warning" style="width: 100%">Submit</button>
                </p>
            </div>
            </form>
        </div>
    </div>
    <script>
        var a = document.getElementById('getproduct_qty').value;
        var b = document.getElementById('getproduct_sn').value;
        var myA = @json($myArrays);
        var myOI = @json($orderInfoArr);

        console.log(myA);
        console.log(myOI);

        $.each(myOI, function(i, item) {
            var lols = myOI[i].product_order_quantity;
            for (z = 0; z < lols; z++) {
                const str = myOI[i].serial_number;

                if (str === null)
                {
                    console.log('EMPTY SERIAL_NUMBER.')
                    $("#droppable"+z+myOI[i].product_id).droppable({
                        hoverClass: 'active',
                        drop: function(event, ui) {
                            this.value = $(ui.draggable).text();
                            $(ui.draggable).hide();
                        }
                    });
                }
                else
                {
                    const sn_num = str.split(', ');
                    $('#droppable'+z+myOI[i].product_id).val(sn_num[z])
                }
            }
        });

        $(function() {
            $(".draggable").draggable({
                revert: true,
                helper: 'clone',
                start: function(event, ui) {
                    $(this).fadeTo('fast', 0.5);
                },
                stop: function(event, ui) {
                    $(this).fadeTo(0, 1);
                    //$(this).hide();
                }
            });
        });
    </script>
@endsection
