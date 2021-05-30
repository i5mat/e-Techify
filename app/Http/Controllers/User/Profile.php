<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ConfirmOrder;
use App\Models\Job;
use App\Models\OrderDetail;
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

        $rmaInfoReseller = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
                'repairs.resolve_solution', 'repairs.receive_at')
            ->get();

        $jobInfo = Job::join('users', 'users.id', '=', 'jobs.user_id')
            ->select('users.name', 'users.email', 'jobs.job_location', 'jobs.job_salary', 'jobs.job_name',
                'jobs.status', 'jobs.job_type', 'jobs.id AS job_id', 'jobs.occupied_by')
            ->get();

        $getCartTotal = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Pay'
            ])
            ->count();

        $getRMATotal = Repair::count();
        $getConfirmOrder = ConfirmOrder::sum('payment_total');
//        $getOrderDetail = OrderDetail::join('products', 'products.id', '=', 'order_details.product_id')
//            ->groupBy('products.product_brand')
//            ->groupBy('month')
//            ->selectRaw("COUNT(MONTH(order_details.created_at)) AS total, product_brand, MONTHNAME(order_details.created_at) AS month")
//            ->get();

//        $getOrderDetail = OrderDetail::join('products', 'products.id', '=', 'order_details.product_id')
//            ->groupBy('products.product_brand')
//            ->groupBy('month')
//            ->selectRaw("COUNT(MONTH(order_details.created_at)) AS total, product_brand, MONTHNAME(order_details.created_at) AS month")
//            ->get();

//        $getOrderDetail = OrderDetail::select(DB::raw('A.product_brand, GROUP_CONCAT(A.CNT SEPARATOR ", ") AS total'))
//            ->from(DB::raw('(SELECT products.product_brand, COUNT(*) AS CNT FROM order_details INNER JOIN products ON products.id = order_details.product_id GROUP BY products.product_brand, MONTH(order_details.created_at) ) A'))
//            ->groupBy('A.product_brand')
//            ->get();

        $getOrderDetail = OrderDetail::select(DB::raw('A.product_brand, GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar, ", ", A.Apr, ", ", A.May, ", ", A.Jun, ", ", A.Jul, ", ", A.Aug, ", ", A.Sep, ", ", A.Oct, ", ", A.Nov, ", ", A.Dec) AS total_per_month'))
            ->from(DB::raw('(SELECT products.product_brand,
                                    SUM(if(MONTH(order_details.created_at) = 1, 1,0)) as Jan,
                                    SUM(if(MONTH(order_details.created_at) = 2, 1,0)) as Feb,
                                    SUM(if(MONTH(order_details.created_at) = 3, 1,0)) as Mar,
                                    SUM(if(MONTH(order_details.created_at) = 4, 1,0)) as Apr,
                                    SUM(if(MONTH(order_details.created_at) = 5, 1,0)) as May,
                                    SUM(if(MONTH(order_details.created_at) = 6, 1,0)) as Jun,
                                    SUM(if(MONTH(order_details.created_at) = 7, 1,0)) as Jul,
                                    SUM(if(MONTH(order_details.created_at) = 8, 1,0)) as Aug,
                                    SUM(if(MONTH(order_details.created_at) = 9, 1,0)) as Sep,
                                    SUM(if(MONTH(order_details.created_at) = 10, 1,0)) as Oct,
                                    SUM(if(MONTH(order_details.created_at) = 11, 1,0)) as Nov,
                                    SUM(if(MONTH(order_details.created_at) = 12, 1,0)) as `Dec`
                                FROM order_details
                                INNER JOIN products ON products.id = order_details.product_id
                                WHERE YEAR(NOW())
                                GROUP by products.product_brand ) A'))
            ->groupBy('A.product_brand')
            ->get();

        $findMonth = OrderDetail::selectRaw('DISTINCT MONTHNAME(created_at) AS monthName')->get();

        $myArray = array();
        foreach ($getOrderDetail as $row) {
            $myArray[] = $row;
        }

        //dd($getCartTotal);

        if (Gate::allows('is-all-roles')) {
            return view('index', compact('rmaInfo', 'jobInfo', 'rmaInfoDistri',
                'getCartTotal', 'getRMATotal', 'getConfirmOrder', 'myArray', 'rmaInfoReseller', 'findMonth', 'getOrderDetail'));
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
