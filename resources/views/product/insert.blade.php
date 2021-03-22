@extends('templates.main')

@section('content')
    <h1>Insert Product</h1>

    <div class="card" style="padding: 20px 40px;">
    <form method="POST" action="{{ route('product.items.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <label for="prod_name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="prod_name" name="prod_name">
            </div>
        </div>
        <div class="row mb-3">
            <label for="prod_sn" class="col-sm-2 col-form-label">Product No.</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="prod_sn" name="prod_sn">
            </div>
        </div>
        <div class="row mb-3">
            <label for="prod_price" class="col-sm-2 col-form-label">Product Price</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="prod_price" name="prod_price">
            </div>
        </div>
        <div class="row mb-3">
            <label for="prod_sn" class="col-sm-2 col-form-label">Product Link</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="prod_link" name="prod_link">
            </div>
        </div>
        <div class="row mb-3">
            <label for="prod_image" class="col-sm-2 col-form-label">Image</label>
            <div class="col-sm-10">
                <input type="file" name="prod_image" id="prod_image" class="form-control">
            </div>
        </div>
        <div class="row mb-3">
            <label for="prod_category" class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <select name="prod_category" id="prod_category" class="form-select">
                    <option value="#">Please select...</option>
                    <option value="CASES">CASES</option>
                    <option value="PSU">PSU</option>
                </select>
            </div>
        </div>
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Brand</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="brandRadio" id="exampleRadios1" value="NZXT" checked>
                    <label class="form-check-label" for="exampleRadios1">
                        <img src="/image/nzxt.png" style="width: 300px; height: 76px; padding-left: 20px;">
                    </label>
                </div><br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="brandRadio" id="exampleRadios2" value="FRACTAL DESIGN">
                    <label class="form-check-label" for="exampleRadios2">
                        <img src="/image/nzxt.png" style="width: 300px; height: 76px; padding-left: 20px;">
                    </label>
                </div>
            </div>
        </fieldset>
        <fieldset class="row mb-3">
            <legend class="col-form-label col-sm-2 pt-0">Warranty Duration</legend>
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="warrantyRadio" id="exampleRadios1" value="1" checked>
                    <label class="form-check-label" for="exampleRadios1">
                        1 year
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="warrantyRadio" id="exampleRadios2" value="2">
                    <label class="form-check-label" for="exampleRadios2">
                        2 year
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="warrantyRadio" id="exampleRadios3" value="3">
                    <label class="form-check-label" for="exampleRadios3">
                        3 year
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="warrantyRadio" id="exampleRadios5" value="5">
                    <label class="form-check-label" for="exampleRadios5">
                        5 year
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="warrantyRadio" id="exampleRadios10" value="10">
                    <label class="form-check-label" for="exampleRadios10">
                        10 year
                    </label>
                </div>
            </div>
        </fieldset>
        <button type="submit" class="btn btn-primary float-end">Submit</button>
    </form>
    </div>
@endsection
