<?php

namespace App\Http\Controllers;

use App\Mail\JobApproved;
use App\Mail\JobDecline;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function jobIndex()
    {
        if (Gate::allows('is-user')) {
            $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
                ->where([
                    'jobs.occupied_by' => Auth::id()
                ])
                ->get();
        } elseif (Gate::allows('is-distributor')) {
            $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
                ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.email_sent')
                ->where([
                    'jobs.user_id' => Auth::id()
                ])
                ->get();
        } elseif (Gate::allows('is-reseller')) {
            $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
                ->get();
        }

        return view('job.index', compact('getJob'));
    }

    public function approveJob($id)
    {
        $findID = Job::find($id);
        $findID->email_sent = 1;
        $findID->save();

        $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.occupied_by' => $findID->occupied_by,
                'jobs.id' => $findID->id
            ])
            ->first();

        $getEmployer = Job::join('users', 'users.id', '=', 'jobs.user_id')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.user_id' => $findID->user_id,
                'jobs.id' => $findID->id
            ])
            ->first();

        Mail::to($getJob->email)->send(new JobApproved($getJob, $getEmployer));
    }

    public function declineJob($id)
    {
        $findID = Job::find($id);

        $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.occupied_by' => $findID->occupied_by,
                'jobs.id' => $findID->id
            ])
            ->first();

        $getEmployer = Job::join('users', 'users.id', '=', 'jobs.user_id')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.user_id' => $findID->user_id,
                'jobs.id' => $findID->id
            ])
            ->first();

        $findID->email_sent = null;
        $findID->occupied_by = null;
        $findID->status = 'Not Occupied';
        $findID->save();

        Mail::to($getJob->email)->send(new JobDecline($getJob, $getEmployer));
    }

    public function emailJob($id)
    {
        $findID = Job::find($id);

        $getJob = Job::join('users', 'users.id', '=', 'jobs.occupied_by')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.occupied_by' => $findID->occupied_by,
                'jobs.id' => $findID->id
            ])
            ->first();

        $getEmployer = Job::join('users', 'users.id', '=', 'jobs.user_id')
            ->select('jobs.job_type', 'users.name', 'jobs.job_name', 'users.email', 'jobs.id', 'jobs.updated_at')
            ->where([
                'jobs.user_id' => $findID->user_id,
                'jobs.id' => $findID->id
            ])
            ->first();

        //Mail::to("ismatazmy10@gmail.com")->send(new JobApproved($getJob, $getEmployer));
        return new JobApproved($getJob, $getEmployer);
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
//        if ($request->has('user_id'))
//            $job_req->save();
//        else
//            dd('WOI ISI LE.');
        $job_req->save();

        return redirect(route('user.userdash'));
    }

    public function updateJob($id, Request $request)
    {
        $findID = Job::findOrFail($id);

        $findID->status = 'Occupied';
        $findID->occupied_by = $request->get('usr_id');

        $findID->save();

        return response()->json(['success'=>'yey! JOB UPDATED!']);
    }

    public function updateJobInfo($id, Request $request)
    {
        $findID = Job::findOrFail($id);

        $findID->job_name = $request->get('scope');
        $findID->job_location = $request->get('location');
        $findID->job_salary = $request->get('rate');
        $findID->job_type = $request->get('type');

        $findID->save();

        return response()->json(['success'=>'yey! JOB INFO UPDATED!']);
    }

    public function updateJobStatus($id, Request $request)
    {
        $findID = Job::findOrFail($id);

        $findID->status = $request->get('status');
        $findID->occupied_by = null;
        $findID->email_sent = null;

        $findID->save();

        return response()->json(['success'=>'yey! JOB STATUS UPDATED!']);
    }
}
