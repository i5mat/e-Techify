<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Job;
use App\Models\Product;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class Profile extends Controller
{
    public function __invoke()
    {
        return view('user.profile');
    }

    public function userDash()
    {
        //if (Gate::allows('is-user'))

        $rmaInfo = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no')
            ->where([
                'repairs.user_id' => Auth::id(),
            ])
            ->get();

        $rmaInfoDistri = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
            'repairs.resolve_solution', 'repairs.receive_at')
            ->where([
                'products.user_id' => Auth::id(),
            ])
            ->get();

        $jobInfo = Job::all();

        if (Gate::allows('is-all-roles')) {
            return view('index', compact('rmaInfo', 'jobInfo', 'rmaInfoDistri'));
        }

        dd('LOL?! ni utk user je.');
    }

    public function userAddressIndex()
    {
        $userinfo = DB::table('addresses')
            ->where('user_id', Auth::id())
            ->get();

        return view('user.address', compact('userinfo'));
    }

    public function addAddress(Request $request)
    {
        if ($request->get('user_unit') == null)
            $insert = new Address([
                "user_id" => Auth::id(),
                "name" => $request->get('user_name'),
                "phone_no" => $request->get('user_phone'),
                "address" => $request->get('user_address'),
                "postcode" => $request->get('user_postcode'),
                "longitude" => $request->get('longitude'),
                "latitude" => $request->get('latitude'),
            ]);
        else
            $insert = new Address([
                "user_id" => Auth::id(),
                "name" => $request->get('user_name'),
                "phone_no" => $request->get('user_phone'),
                "address" => $request->get('user_unit').', '.$request->get('user_address'),
                "postcode" => $request->get('user_postcode'),
                "longitude" => $request->get('longitude'),
                "latitude" => $request->get('latitude'),
            ]);

        $insert->save();
        $request->session()->flash('success', 'Address Inserted');

        return redirect(route('user.useraddress'));
    }

    public function destroyAddress($id, Request $request)
    {
        Address::destroy($id);
        $request->session()->flash('danger', 'Address Deleted');

        return redirect(route('user.useraddress'));
    }
}
