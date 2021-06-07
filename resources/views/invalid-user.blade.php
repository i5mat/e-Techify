@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

    <h1 class="display-3 text-center">
        Please ask admin to approve your account!
    </h1>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        myAlert();

        function myAlert() {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Logged In!',
                showConfirmButton: false,
                timer: 2000
            })
        }
    </script>
@endsection
