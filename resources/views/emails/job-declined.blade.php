@component('mail::message')
# Your Application Declined!

Hi! {{ $getJob->name }} your application for this job are declined.

@component('mail::panel')
Please contact the respective employer for further instructions, {{ $getEmployer->name }}
@endcomponent

@component('mail::subcopy')
@endcomponent

Thanks ❤,<br>
{{ $getJob->name }}
@endcomponent
