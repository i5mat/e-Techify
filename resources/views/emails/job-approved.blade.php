@component('mail::message')
# Your Application Success!

Hi! {{ $getJob->name }} your application for this job are approved.

@component('mail::panel')
    Please contact the respective employer for further instructions, {{ $getEmployer->name }}
@endcomponent

# Job Details:<br><br>

## Job Name:<br>
{{ $getJob->job_name }} <br><br>
## Applicant Name:<br>
{{ $getJob->name }} <br><br>
## Applied On:<br>
{{ date('jS F Y h:i A', strtotime($getJob->updated_at)) }} <br><br>
## Contact:<br>
{{ $getEmployer->email }}

@component('mail::subcopy')
@endcomponent

Thanks ❤,<br>
{{ $getJob->name }}
@endcomponent
