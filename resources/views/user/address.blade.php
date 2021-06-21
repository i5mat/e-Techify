@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

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
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="test">
            <label for="user_name">Name</label>
        </div>

        <label for="user_unit" class="error"></label>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_unit" name="user_unit" placeholder="test">
            <label for="floatingTextarea2">Apartment, unit, suite, or floor #</label>
        </div>

        <label for="user_address" class="error"></label>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="user_address" name="user_address" placeholder="test">
            <label for="floatingTextarea2">Address</label>
        </div>

        <div class="row g-2 mb-3">
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
        <div class="row g-2 mb-3">
            <div class="col-md">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="longitude" id="longitude" readonly>
                    <label for="autocomplete">Long</label>
                </div>
            </div>
            <div class="col-md">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="latitude" id="latitude" readonly>
                    <label for="autocomplete">Lat</label>
                </div>
            </div>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
            <label class="form-check-label" for="flexCheckChecked">
                Click here if in google suggestion does not have your specific location or your postcode is wrong.
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
                    <th scope="col">Default</th>
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
                            <button @if($i->default_status == 1) disabled @endif type="button" class="btn btn-info" style="background-color: transparent; border: none"
                                    onclick="event.preventDefault();
                                        document.getElementById('delete-address-{{ $i->id }}').submit()">
                                <img src="/image/close.png">
                            </button>

                            <form id="delete-address-{{ $i->id }}" action="{{ route('user.userdel_address', $i->id) }}" method="POST" style="display: none">
                                @csrf
                                @method("DELETE")
                            </form>
                        </td>
                        <td>
                            @if($i->default_status == 1)
                                <button type="button" class="btn btn-warning" id="btn_default{{ $loop->iteration }}" data-id="{{ $i->id }}">
                                    Update
                                </button>
                            @elseif ($countActive == 0)
                                <button type="button" class="btn btn-primary" id="btn_default{{ $loop->iteration }}" data-id="{{ $i->id }}">
                                    Default
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jC89-qmnCWo2FSQy8zg0LxOvNlncp9I&libraries=places&sensor=false"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            $('#user_postcode').attr('readonly', true);
            $("#flexCheckChecked").change(function() {
                if($(this).prop('checked')) {
                    $('#user_unit').show();
                    $('#user_postcode').attr('readonly', false);
                } else {
                    $('#user_unit').hide();
                    $('#user_postcode').attr('readonly', true);
                }
            });
        });

        var total_items = {{ $userinfo->count() }};
        for (i = 1; i <= total_items; i++) {
            $("#btn_default"+i).click(function (e) {

                e.preventDefault();

                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");

                $.ajax({
                    type: 'PATCH',
                    url: "http://127.0.0.1:8000/user/updateAddressStatus/" + id,
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    success: function (data) {
                        if (data['success']) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated',
                                text: 'Your default status have been updated',
                                confirmButtonText: `Click me!`,
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                        else
                            alert('EXISTING.')

                    }
                });
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var options = {
                componentRestrictions: {country: "MY"}
            };
            var input = document.getElementById('user_address');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            autocomplete.addListener('place_changed', function() {
                var place = autocomplete.getPlace();
                console.log(place)
                $('#latitude').val(place.geometry['location'].lng());
                $('#longitude').val(place.geometry['location'].lat());
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
