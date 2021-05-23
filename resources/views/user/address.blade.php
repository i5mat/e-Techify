@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Insert Address <img src="/image/locations.png"/></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="padding: 20px 40px;">
    <form method="POST" action="{{ route('user.userinsertaddress') }}" enctype="multipart/form-data" id="insert-address-form">
        @csrf

        <label for="user_name" class="error"></label>
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="test">
            <label for="user_name">Name</label>
        </div>

        <label for="user_unit" class="error"></label>
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="user_unit" name="user_unit" placeholder="test">
            <label for="floatingTextarea2">Apartment, unit, suite, or floor #</label>
        </div>

        <label for="user_address" class="error"></label>
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="user_address" name="user_address" placeholder="test">
            <label for="floatingTextarea2">Address</label>
        </div>

        <div class="row g-2">
            <div class="col-md">
                <div class="form-floating">
                    <input type="text" class="form-control" id="user_phone" name="user_phone" placeholder="test">
                    <label for="user_phone">Phone No.</label>
                </div>
                <label for="user_phone" class="error"></label>
            </div>
            <div class="col-md">

                <div class="form-floating">
                    <input type="number" class="form-control" id="user_postcode" name="user_postcode" placeholder="test">
                    <label for="user_postcode">Insert Postcode</label>
                </div>
                <label for="user_postcode" class="error"></label>
            </div>
        </div>
        <div class="row g-2 mb-2">
            <div class="col-md">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="latitude" id="latitude" readonly>
                    <label for="autocomplete">Lat</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="longitude" id="longitude" readonly>
                    <label for="autocomplete">Long</label>
                </div>
            </div>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
            <label class="form-check-label" for="flexCheckChecked">
                Click here if in google suggestion does not have your specific location
            </label>
        </div>
        <button type="submit" class="btn btn-primary float-end" style="width: 100%">Submit</button>
    </form>
    </div>

    <div class="card text-center" style="margin-top: 10px">
        <div class="card-body">
            <table class="table" style="margin-top: 20px">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Address</th>
                    <th scope="col">Created On</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($userinfo as $i)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>
                            <b>{{ $i->name }}</b>
                        </td>
                        <td>
                            (+60) {{ $i->phone_no }}
                        </td>
                        <td>
                            {{ $i->address }}
                        </td>
                        <td>
                            {{ date('Y-m-d H:i:s A', strtotime($i->created_at)) }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-info" style="background-color: transparent; border: none"
                                    onclick="event.preventDefault();
                                        document.getElementById('delete-address-{{ $i->id }}').submit()">
                                <img src="/image/close.png">
                            </button>

                            <form id="delete-address-{{ $i->id }}" action="{{ route('user.userdel_address', $i->id) }}" method="POST" style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jC89-qmnCWo2FSQy8zg0LxOvNlncp9I&libraries=places&sensor=false"></script>

    <script>
        $(document).ready(function() {
            $("#insert-address-form").validate({
                rules: {
                    user_name : {
                        required: true
                    },
                    user_phone: {
                        required: true,
                        number: true,
                        min: 11
                    },
                    user_postcode: {
                        required: true,
                        number: true,
                        maxlength: 5
                    },
                    user_address: {
                        required: true,
                    }
                },
                messages : {
                    user_name: {
                        required: "Please enter name"
                    },
                    user_phone: {
                        required: "Please enter phone number",
                        number: "Please enter phone number as a numerical value",
                    },
                    user_postcode: {
                        maxLength: "Enter only 5 numbers",
                    },
                    user_address: {
                        required: "Enter address"
                    }

                }
            });

            $('#user_unit').hide();
            $("#flexCheckChecked").change(function() {
                if($(this).prop('checked')) {
                    $('#user_unit').show();
                } else {
                    $('#user_unit').hide();
                }
            });
        });

        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('user_address');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                console.log(place)
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
                for (var i = 0; i < place.address_components.length; i++) {
                    for (var j = 0; j < place.address_components[i].types.length; j++) {
                        if (place.address_components[i].types[j] === "postal_code") {
                            $('#user_postcode').val(place.address_components[i].long_name);
                            $('#user_postcode').attr('readonly', true);
                        }
                    }
                }
            });
        }
    </script>
@endsection
