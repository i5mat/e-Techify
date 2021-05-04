<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function jobIndex()
    {
        return view('job.index');
    }

    public function jobInsert(Request $request)
    {
        $job_req = new Job([
            "user_id" => Auth::id(),
            "job_name" => $request->get('job_name'),
            "job_salary" => $request->get('job_salary'),
            "job_location" => $request->get('job_loc'),
            "job_type" => $request->get('job_type'),
            "status" => 'Not Occupied'
        ]);
        $job_req->save(); // Finally, save the record.

        return redirect(route('user.userdash'));
    }
}
