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

    <div class="card mb-2" style="padding: 20px 40px;">
        <div class="card-body" >
            <div class="col text-center">
                <img id="product_pic" width="300" height="300" />

                <div class="form-floating" style="margin-bottom: 10px">
                    <select class="form-select" id="SelectProductID" aria-label="Floating label select example" style="height: 60px">
                        @foreach($fetchProduct as $prod)
                            <option value="{{ $prod->product_image_path }}" selected>{{ $prod->id }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-floating" style="margin-bottom: 10px">
                    <select class="form-select" id="SelectProduct" aria-label="Floating label select example" style="height: 60px" onchange="myFunctions()">
                        @foreach($fetchProduct as $prod)
                            <option value="{{ $prod->id }}" selected>{{ $prod->product_name }}</option>
                        @endforeach
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

    <div class="card">
        <div class="card-body" >
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        myFunctions();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
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

        function myFunctions() {
            $('#SelectProductID').attr('hidden', true);

            $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
            var value = $("#SelectProduct option:selected");
            var value2 = $("#SelectProductID option:selected");

            // document.getElementById('p_name').innerHTML = value.text().bold();
            // document.getElementById('products_id_dist').innerHTML = value2.text();
            document.getElementById("product_pic").src = "/storage/product/" + value2.val();

            $('#SelectProduct').change(function(){
                $("#SelectProductID option").eq($(this).find(':selected').index()).prop('selected',true);
                var value = $("#SelectProduct option:selected");
                var value2 = $("#SelectProductID option:selected");

                // document.getElementById('p_name').innerHTML = value.text().bold();
                // document.getElementById('products_id_dist').innerHTML = value2.text();
                document.getElementById("product_pic").src = "/storage/product/" + value2.val();
            });
        }
    </script>
@endsection
