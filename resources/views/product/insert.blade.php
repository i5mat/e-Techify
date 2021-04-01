@extends('templates.main')

@section('content')
    <h1>Insert Product</h1>

    <div class="card" style="padding: 20px 40px;">
    <form method="POST" action="{{ route('product.items.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="prod_name" name="prod_name" placeholder="test">
            <label for="prod_name">Product Name</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="prod_sn" name="prod_sn" placeholder="test">
            <label for="prod_sn">Product No.</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="prod_price" name="prod_price" placeholder="test">
            <label for="prod_price">Product Price</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="prod_link" name="prod_link" placeholder="test">
            <label for="prod_link">Product Link</label>
        </div>
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

        <div class="form-floating mb-3">
            <select class="form-select" name="prod_brand" id="prod_brand">
                <option selected>Please select...</option>
                <option value="NZXT">NZXT</option>
                <option value="FRACTAL DESIGN">FRACTAL DESIGN</option>
            </select>
            <label for="prod_brand">Product Brand</label>
        </div>

        <button type="submit" class="btn btn-primary float-end">Submit</button>
    </form>
    </div>
@endsection
