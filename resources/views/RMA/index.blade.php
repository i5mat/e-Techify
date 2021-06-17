@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <h1 class="display-2 text-center">e-RMA Product <img src="/image/tool-box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Submit your RMA request here.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="card" style="padding: 20px 40px;">
    <form method="POST" action="{{ route('rma.new.post.request') }}" enctype="multipart/form-data" id="rma_form">
        @csrf

        <dl class="row">
            <dt class="col-sm-3">Name & Phone No.</dt>
            <dd class="col-sm-9" id="user_name_display"><b>Select recipient</b></dd>

            <dt class="col-sm-3">Address</dt>
            <dd class="col-sm-9">
                <p id="address_display">Select address</p>
                <input type="number" id="get_add_id_rma" name="get_add_id_rma" hidden>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelectAddress" aria-label="Floating label select example" style="height: 60px" onchange="myFunctions()">
                        <option disabled>-</option>
                        @foreach($retrieveAddress as $ui)
                            <option value="{{ $ui->address }}" selected>{{ $ui->address }}</option>
                        @endforeach
                    </select>
                    <label for="floatingSelect">Select Address</label>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelectAddress2" aria-label="Floating label select example" style="height: 60px; margin-top: 10px">
                        <option>-</option>
                        @foreach($retrieveAddress as $ui)
                            <option value="{{ $ui->name }} (+60) {{ $ui->phone_no }}" selected>{{ $ui->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-floating">
                    <select class="form-select" id="floatingSelectAddress3" aria-label="Floating label select example" style="height: 60px; margin-top: 10px">
                        <option>-</option>
                        @foreach($retrieveAddress as $ui)
                            <option value="{{ $ui->id }}" selected>{{ $ui->id }}</option>
                        @endforeach
                    </select>
                </div>
            <dt class="col-sm-3">Date of Purchase</dt>
            <dd class="col-sm-9">
                <label for="date-purchased" class="error"></label>
                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="date-purchased" name="date-purchased">
                    <label for="date-purchased">Date Of Purchase</label>
                </div>
            </dd>
            <dt class="col-sm-3">Proof of Purchase</dt>
            <dd class="col-sm-9">
                <div class="form-floating mb-3">
                    <div class="col-sm-auto">
                        <input accept="application/pdf" type="file" name="proof_of_purchase_file" id="proof_of_purchase_file" class="form-control">
                    </div>
                </div>
            </dd>
        </dl>
        <div class="row g-2 mb-3">
            <div class="col-md">
                <div class="form-floating">
                    <select class="form-select" id="prod_brand" name="prod_brand">
                        <option value=''><strong>Name</strong></option>
                        @foreach($getProductBrand as $prod_brand)
                            <option value="{{ $prod_brand->product_brand }}">{{ $prod_brand->product_brand }}</option>
                        @endforeach
                    </select>
                    <label for="prod_brand">Brand</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating">
                    <select class="form-select" name="prod_name" id="prod_name">
                        <option value=''><strong>Products</strong></option>
                    </select>
                    <label for="prod_name">Product</label>
                </div>
            </div>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md">
                <label for="product_sn" class="error"></label>
                <div class="form-floating">
                    <input type="text" class="form-control" id="product_sn" name="product_sn" placeholder="test">
                    <label for="product_sn">Serial Number</label>
                </div>
            </div>
            <div class="col-md">
                <label for="product_sn_confirm" class="error"></label>
                <div class="form-floating">
                    <input type="text" class="form-control" id="product_sn_confirm" name="product_sn_confirm" placeholder="test">
                    <label for="product_sn_confirm">Confirm Serial Number</label>
                    <div style="margin-top: 3px;" id="CheckMatch"></div>
                </div>
            </div>
        </div>

        <label for="reason_field" class="error"></label>
        <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Leave a comment here" id="reason_field" name="reason_field" style="height: 100px"></textarea>
            <label for="reason_field">Comments</label>
        </div>

        <button type="button" class="btn btn-primary float-end" style="width: 100%" id="btn_submit_rma">Submit</button>
    </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        myFunctions();

        $(function(){
            var dtToday = new Date();

            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var maxDate = year + '-' + month + '-' + day;
            $('#date-purchased').attr('max', maxDate);
        });

        $("#rma_form").validate({
            rules: {
                proof_of_purchase_file : {
                    required: true
                },
                product_sn: {
                    required: true
                },
                product_sn_confirm: {
                    required: true
                },
                reason_field: {
                    required: true
                }
            },
            messages : {
                proof_of_purchase_file: {
                    required: "Please attach proof of purchase in PDF form"
                },
                product_sn: {
                    required: "Please enter serial number"
                },
                product_sn_confirm: {
                    required: "Please enter serial number confirmation"
                },
                reason_field: {
                    required: "Please enter reason"
                }
            }
        });

        $("#btn_submit_rma").click(function() {
            if ($("#prod_brand :selected").text() === 'Name' || $("#prod_name :selected").text() === 'Products') {
                Swal.fire(
                    'NaN Value',
                    'Please select input brand and product',
                    'error'
                )
            } else
                $("#rma_form").submit();
        });

        var myArr = @json($myArray);
        console.log(myArr)

        $("#prod_brand").change(function(){
            var company = $(this).val();
            var options =  '<option value=""><strong>Products</strong></option>';
            $(myArr).each(function(index, value){
                if(value.product_brand == company){
                    options += '<option value="'+value.id+'">'+value.product_name+'</option>';
                }
            });

            $('#prod_name').html(options);
        });

        $("#product_sn_confirm").on('keyup', function(){
            var p_sn = $("#product_sn").val();
            var p_sn_c = $("#product_sn_confirm").val();
            if (p_sn != p_sn_c)
            {
                $('#btn_submit_rma').attr('disabled', true);
                $("#CheckMatch").html("SN does not match !").css("color","red");
            }
            else
            {
                $('#btn_submit_rma').attr('disabled', false);
                $("#CheckMatch").html("SN match !").css("color","green");
            }
        });

        function myFunctions() {

            $('#floatingSelectAddress2').attr('hidden', true);
            $('#floatingSelectAddress3').attr('hidden', true);

            $("#floatingSelectAddress2 option").eq($(this).find(':selected').index()).prop('selected',true);
            $("#floatingSelectAddress3 option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#floatingSelectAddress option:selected");
            var value2 = $("#floatingSelectAddress2 option:selected");
            var value3 = $("#floatingSelectAddress3 option:selected");

            document.getElementById('address_display').innerHTML = value.text();
            document.getElementById('user_name_display').innerHTML = value2.val().bold();
            document.getElementById('get_add_id_rma').value = value3.val();

            $('#floatingSelectAddress').change(function(){
                $("#floatingSelectAddress2 option").eq($(this).find(':selected').index()).prop('selected',true);
                $("#floatingSelectAddress3 option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#floatingSelectAddress option:selected");
                var value2 = $("#floatingSelectAddress2 option:selected");
                var value3 = $("#floatingSelectAddress3 option:selected");

                document.getElementById('address_display').innerHTML = value.text();
                document.getElementById('user_name_display').innerHTML = value2.val().bold();
                document.getElementById('get_add_id_rma').value = value3.val();
            });
        }
    </script>
@endsection
