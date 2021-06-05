@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <h1 class="display-2 text-center">Insert Job <i class="fa fa-briefcase"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('job.request.new') }}" id="form_job">
                @csrf

                <div class="row g-2">
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="job_name" name="job_name" placeholder="test">
                            <label for="job_name">Job Name</label>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="job_salary" name="job_salary" placeholder="test" min="1">
                                <label for="job_salary">Job Salary</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <select class="form-select" name="job_type" id="job_type" required>
                                <option selected>Please select...</option>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Temporary">Temporary</option>
                                <option value="Permanent">Permanent</option>
                            </select>
                            <label for="job_type">Job Type</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="job_loc" name="job_loc" placeholder="test" maxlength="39">
                    <label for="job_loc">Job Location</label>
                </div>

                <button type="button" class="btn btn-primary float-end" style="width: 100%" id="btn_submit_job">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#btn_submit_job").click(function() {
            if ($("#job_name").val() === '' && $("#job_salary").val() === '' && $("#job_loc").val() === '' && $("#job_type :selected").text() === 'Please select...') {
                Swal.fire(
                    'Input NULL',
                    'Please input all fields with relevant information',
                    'error'
                )
            } else
                $("#form_job").submit();
        });

    </script>
@endsection
