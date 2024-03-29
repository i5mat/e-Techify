@extends('templates.main')

@section('content')
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <h1 class="display-2 text-center">Job <i class="fa fa-rocket"></i></h1>

    <figure class="text-center">
        <blockquote class="blockquote">
            <p>Manage jobs info.</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            Prepared by <cite title="Source Title">Wan Ismat</cite>
        </figcaption>
    </figure>

    @can('is-reseller-distributor')
    <div class="card">
        <div class="card-body border-3 border-bottom border-warning">
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
                                <input type="number" class="form-control" id="job_salary" name="job_salary" placeholder="test" min="1" oninput="validity.valid||(value='');">
                                <label for="job_salary">Job Salary</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-floating">
                            <select class="form-select" name="job_type" id="job_type" required>
                                <option selected>Please select...</option>
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
    @endcan

    <div class="card shadow mt-3">
        @if($getJob->count() <= 0)
            <div class="card-body">
                <img src="/image/no-application.png" style="width: 400px; height: 400px; display: block; margin-left: auto; margin-right: auto">
                <h1 class="text-center display-6">
                    No application made in the system.
                </h1>
            </div>
        @else
            <div class="card-body">
                <table class="display" id="job-applicant-table" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Applicant</th>
                        <th scope="col">Job Name</th>
                        <th scope="col">Email</th>
                        <th scope="col" style="text-align: center">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($getJob as $job)
                        <tr>
                            <td>
                                <span class="badge rounded-pill bg-primary" style="color: white">{{ $job->job_type }}</span>
                            </td>
                            <td>{{ $job->name }}</td>
                            <td>{{ $job->job_name }}</td>
                            <td>{{ $job->email }}</td>
                            <td style="text-align: center">
                                @can('is-distributor')
                                    @if(!isset($job->email_sent))
                                        <button class="btn btn-success rounded-pill" id="accept_applicant_btn{{ $loop->iteration }}" data-id="{{ $job->id }}">Accept</button>
                                        <button class="btn btn-danger rounded-pill" id="reject_applicant_btn{{ $loop->iteration }}" data-id="{{ $job->id }}">Decline</button>
                                    @else
                                        Responded 💨
                                    @endif
                                @else
                                    @if($job->email_sent == 1)
                                        <i class="fa fa-check fa-2x"></i>
                                    @else
                                        <i class="fa fa-times fa-2x"></i>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#job-applicant-table').DataTable({
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });

        @can('is-reseller-distributor')
        $("#btn_submit_job").click(function() {
            if ($("#job_name").val() === '' || $("#job_salary").val() === '' || $("#job_loc").val() === '' || $("#job_type :selected").text() === 'Please select...') {
                Swal.fire(
                    'Null Value',
                    'Please input all fields with relevant information.',
                    'error'
                )
            } else
                $("#form_job").submit();
        });
        @endcan

        var myA = @json($getJob);
        console.log(myA);
        var name = '{{ env('APP_URL') }}';

        for (z = 1; z <= myA.length; z++) {
            $("#accept_applicant_btn"+z).click(function () {
                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    icon: 'success',
                    title: 'Accepting your applicant request, sending an email to applicant',
                    didOpen: function () {
                        Swal.showLoading()
                        $.ajax(
                            {
                                url: name+"/job/job-approved/" + id,
                                type: 'POST',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
                                success: function () {
                                    location.reload();
                                }
                            });
                    }
                })
            });

            $("#reject_applicant_btn"+z).click(function () {
                var id = $(this).data("id");
                var token = $("meta[name='csrf-token']").attr("content");

                Swal.fire({
                    icon: 'error',
                    title: 'Rejecting your applicant request, sending an email to applicant 😥',
                    didOpen: function () {
                        Swal.showLoading()
                        $.ajax(
                            {
                                url: name+"/job/job-declined/" + id,
                                type: 'POST',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
                                success: function () {
                                    location.reload();
                                }
                            });
                    }
                })
            });
        }
    </script>
@endsection
