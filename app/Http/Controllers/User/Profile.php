<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Job;
use App\Models\Product;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Profile extends Controller
{
    public function __invoke()
    {
        return view('user.profile');
    }

    public function userDash()
    {
        $rmaInfo = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path')
            ->where([
                'repairs.user_id' => Auth::id(),
            ])
            ->get();

        $jobInfo = Job::all();

        return view('index', compact('rmaInfo', 'jobInfo'));
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
        $insert = new Address([
            "user_id" => Auth::id(),
            "name" => $request->get('user_name'),
            "phone_no" => $request->get('user_phone'),
            "address" => $request->get('user_address'),
            "postcode" => $request->get('user_postcode'),
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
