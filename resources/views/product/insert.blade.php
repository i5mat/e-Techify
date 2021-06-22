@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Create <img src="/image/box.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Insert product.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    <div class="card" style="padding: 20px;">
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
            <div class="col-md" id="div_default_prod_brand">
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
            <div class="col-md" id="div_new_prod_brand">
                <div class="form-floating">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="new_prod_brand" name="new_prod_brand" placeholder="test">
                        <label for="new_prod_brand">Product Brand</label>
                    </div>
                </div>
                <label for="new_prod_brand" class="error"></label>
            </div>
            <div class="col-md">
                <div class="form-floating">
                    <div class="form-floating">
                        <input type="number" class="form-control" id="prod_stock" name="prod_stock" placeholder="test">
                        <label for="prod_price">Product Stock</label>
                    </div>
                </div>
                <label for="prod_stock" class="error"></label>
            </div>
        </div>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
            <label class="form-check-label" for="flexCheckChecked">
                Click here if you want to insert new product brand.
            </label>
        </div>

        <button type="submit" class="btn btn-primary float-end" style="width: 100%">Submit</button>
    </form>
    </div>

    <script>
        $('#div_new_prod_brand').hide();
        $("#flexCheckChecked").change(function() {
            if($(this).prop('checked')) {
                $('#div_new_prod_brand').show();
                $('#div_default_prod_brand').hide();
            } else {
                $('#div_new_prod_brand').hide();
                $('#div_default_prod_brand').show();
            }
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
    </script>
@endsection
