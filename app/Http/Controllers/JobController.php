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
        if ($request->has('user_id'))
            $job_req->save();
        else
            dd('WOI ISI LE.');

        return redirect(route('user.userdash'));
    }

    public function updateJob($id, Request $request)
    {
        $findID = Job::findOrFail($id);

        $findID->status = 'Occupied';
        $findID->occupied_by = $request->get('usr_id');

        $findID->save();

        return response()->json(['success'=>'yey! RMA UPDATED!']);
    }
}
