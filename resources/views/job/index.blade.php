@extends('templates.main')

@section('content')
    <h1 class="display-2 text-center">Insert Job <i class="fa fa-briefcase"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>A well-known quote, contained in a blockquote element.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Someone famous in <cite title="Source Title">Source Title</cite>
        </figcaption>
    </figure>

    <div class="card" style="padding: 20px 40px;">
        <div class="card-body" >
            <form method="POST" action="{{ route('job.request.new') }}">
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
                            <select class="form-select" name="job_type" id="job_type">
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

                <button type="submit" class="btn btn-primary float-end" style="width: 100%">Submit</button>
            </form>
        </div>
        </div>
    </div>
@endsection
