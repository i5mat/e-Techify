<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ConfirmOrder;
use App\Models\DistributorProduct;
use App\Models\Job;
use App\Models\Order;
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

    public function fetch_data(Request $request)
    {
        if ($request->ajax())
        {
            if (Gate::allows('is-reseller')) {
                $rmaInfoReseller = Repair::join('products', 'products.id', '=', 'repairs.product_id')
                    ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                        'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
                        'repairs.resolve_solution', 'repairs.receive_at')
                    ->paginate(2);

                return view('pagination', compact('rmaInfoReseller'))->render();
            } elseif (Gate::allows('is-distributor')) {
                $rmaInfoDistri = Repair::join('products', 'products.id', '=', 'repairs.product_id')
                    ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                        'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
                        'repairs.resolve_solution', 'repairs.receive_at')
                    ->where([
                        'products.user_id' => Auth::id(),
                    ])
                    ->paginate(2);
                return view('pagination', compact('rmaInfoDistri'))->render();
            } elseif (Gate::allows('is-user')) {
                $rmaInfo = Repair::join('products', 'products.id', '=', 'repairs.product_id')
                    ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                        'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no')
                    ->where([
                        'repairs.user_id' => Auth::id(),
                    ])
                    ->paginate(2);
                return view('pagination', compact('rmaInfo'))->render();
            }
        }
    }

    public function userDash()
    {
        $rmaInfo = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no')
            ->where([
                'repairs.user_id' => Auth::id(),
            ])
            ->paginate(2);

        $rmaInfoDistri = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
            'repairs.resolve_solution', 'repairs.receive_at')
            ->where([
                'products.user_id' => Auth::id(),
            ])
            ->paginate(2);

        $rmaInfoReseller = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->select('products.product_image_path', 'products.product_name', 'products.product_sn', 'repairs.sn_no',
                'repairs.status', 'repairs.id', 'repairs.file_path', 'repairs.reason', 'repairs.created_at', 'repairs.tracking_no',
                'repairs.resolve_solution', 'repairs.receive_at')
            ->paginate(2);

        if (Gate::allows('is-user-reseller')) {
            $jobInfo = Job::join('users', 'users.id', '=', 'jobs.user_id')
                ->select('users.name', 'users.email', 'jobs.job_location', 'jobs.job_salary', 'jobs.job_name',
                    'jobs.status', 'jobs.job_type', 'jobs.id AS job_id', 'jobs.occupied_by')
                ->get();
        } elseif (Gate::allows('is-distributor')) {
            $jobInfo = Job::join('users', 'users.id', '=', 'jobs.user_id')
                ->select('users.name', 'users.email', 'jobs.job_location', 'jobs.job_salary', 'jobs.job_name',
                    'jobs.status', 'jobs.job_type', 'jobs.id AS job_id', 'jobs.occupied_by')
                ->where('jobs.user_id', Auth::id())
                ->get();
        }

        $getCartTotal = OrderDetail::join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Pay'
            ])
            ->count();

        $getRMATotal = Repair::count();
        $getConfirmOrder = ConfirmOrder::sum('payment_total');
        $getConfirmOrderMonthly = ConfirmOrder::selectRaw("sum(confirm_orders.payment_total) as sale_per_month")
            ->whereRaw("MONTH(created_at) = MONTH(curdate())")->first();

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
                                INNER JOIN orders ON orders.id = order_details.order_id
                                WHERE YEAR(NOW()) AND orders.order_status != "Cancelled" AND orders.order_status != "To Pay"
                                GROUP by products.product_brand ) A'))
            ->groupBy('A.product_brand')
            ->get();

        $x = Auth::id();
        $getDataDistributor = OrderDetail::select(DB::raw('A.product_brand, GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar, ", ", A.Apr, ", ", A.May, ", ", A.Jun, ", ", A.Jul, ", ", A.Aug, ", ", A.Sep, ", ", A.Oct, ", ", A.Nov, ", ", A.Dec) AS total_per_month'))
            ->from(DB::raw("(SELECT products.product_brand,
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
                                INNER JOIN orders ON orders.id = order_details.order_id
                                WHERE YEAR(NOW()) AND products.user_id = '$x' AND orders.order_status != 'Cancelled' AND orders.order_status != 'To Pay'
                                GROUP by products.product_brand ) A"))
            ->groupBy('A.product_brand')
            ->get();

        $getTotalSalesReseller = ConfirmOrder::select(DB::raw('GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar, ", ", A.Apr, ", ", A.May, ", ", A.Jun, ", ", A.Jul, ", ", A.Aug, ", ", A.Sep, ", ", A.Oct, ", ", A.Nov, ", ", A.Dec) AS total_per_month_reseller'))
            ->from(DB::raw("(SELECT
                                    SUM(if(MONTH(confirm_orders.created_at) = 1, confirm_orders.payment_total,0)) as Jan,
                                    SUM(if(MONTH(confirm_orders.created_at) = 2, confirm_orders.payment_total,0)) as Feb,
                                    SUM(if(MONTH(confirm_orders.created_at) = 3, confirm_orders.payment_total,0)) as Mar,
                                    SUM(if(MONTH(confirm_orders.created_at) = 4, confirm_orders.payment_total,0)) as Apr,
                                    SUM(if(MONTH(confirm_orders.created_at) = 5, confirm_orders.payment_total,0)) as May,
                                    SUM(if(MONTH(confirm_orders.created_at) = 6, confirm_orders.payment_total,0)) as Jun,
                                    SUM(if(MONTH(confirm_orders.created_at) = 7, confirm_orders.payment_total,0)) as Jul,
                                    SUM(if(MONTH(confirm_orders.created_at) = 8, confirm_orders.payment_total,0)) as Aug,
                                    SUM(if(MONTH(confirm_orders.created_at) = 9, confirm_orders.payment_total,0)) as Sep,
                                    SUM(if(MONTH(confirm_orders.created_at) = 10, confirm_orders.payment_total,0)) as Oct,
                                    SUM(if(MONTH(confirm_orders.created_at) = 11, confirm_orders.payment_total,0)) as Nov,
                                    SUM(if(MONTH(confirm_orders.created_at) = 12, confirm_orders.payment_total,0)) as `Dec`
                                FROM confirm_orders
                                WHERE YEAR(NOW())) A"))
            ->get();

        $getPieChartData = OrderDetail::select(DB::raw('products.product_brand, SUM(order_details.product_order_quantity) AS tot_qty'))
            ->from(DB::raw('order_details INNER JOIN products ON products.id = order_details.product_id'))
            ->where('products.user_id', $x)
            ->groupBy('products.product_brand')
            ->get();

        $getTotalSold = OrderDetail::join('products', 'products.id', '=', 'order_details.product_id')
            ->where('products.user_id', $x)
            ->sum('order_details.product_order_quantity');

        $getRMA = Repair::join('products', 'products.id', '=', 'repairs.product_id')
            ->where('products.user_id', $x)
            ->count();

        $getCustTotalSpend = ConfirmOrder::join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where('orders.user_id', $x)
            ->sum('confirm_orders.payment_total');

        $getCustTotalOrder = Order::where('user_id', $x)
            ->where('order_status', '!=', 'Cancelled')
            ->count();

        $findMonth = OrderDetail::selectRaw('DISTINCT MONTHNAME(created_at) AS monthName')->get();

        $myArray = array();
        foreach ($getOrderDetail as $row) {
            $myArray[] = $row;
        }

        $getQ1 = ConfirmOrder::select(DB::raw('GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar) AS Q1'))
            ->from(DB::raw("(SELECT MONTHNAME(confirm_orders.created_at) AS month,
                    SUM(if(MONTH(confirm_orders.created_at) = 1, confirm_orders.payment_total,0)) as Jan,
                    SUM(if(MONTH(confirm_orders.created_at) = 2, confirm_orders.payment_total,0)) as Feb,
                    SUM(if(MONTH(confirm_orders.created_at) = 3, confirm_orders.payment_total,0)) as Mar
                FROM confirm_orders
                WHERE YEAR(NOW())) A"))
            ->get();

        $getQ2 = ConfirmOrder::select(DB::raw('GROUP_CONCAT(A.Apr, ", ", A.May, ", ", A.Jun) AS Q2'))
            ->from(DB::raw("(SELECT MONTHNAME(confirm_orders.created_at) AS month,
                    SUM(if(MONTH(confirm_orders.created_at) = 4, confirm_orders.payment_total,0)) as Apr,
                    SUM(if(MONTH(confirm_orders.created_at) = 5, confirm_orders.payment_total,0)) as May,
                    SUM(if(MONTH(confirm_orders.created_at) = 6, confirm_orders.payment_total,0)) as Jun
                FROM confirm_orders
                WHERE YEAR(NOW())) A"))
            ->get();

        $getQ3 = ConfirmOrder::select(DB::raw('GROUP_CONCAT(A.Jul, ", ", A.Aug, ", ", A.Sep) AS Q3'))
            ->from(DB::raw("(SELECT MONTHNAME(confirm_orders.created_at) AS month,
                    SUM(if(MONTH(confirm_orders.created_at) = 7, confirm_orders.payment_total,0)) as Jul,
                    SUM(if(MONTH(confirm_orders.created_at) = 8, confirm_orders.payment_total,0)) as Aug,
                    SUM(if(MONTH(confirm_orders.created_at) = 9, confirm_orders.payment_total,0)) as Sep
                FROM confirm_orders
                WHERE YEAR(NOW())) A"))
            ->get();

        $getQ4 = ConfirmOrder::select(DB::raw('GROUP_CONCAT(A.Oct, ", ", A.Nov, ", ", A.Dec) AS Q4'))
            ->from(DB::raw("(SELECT MONTHNAME(confirm_orders.created_at) AS month,
                    SUM(if(MONTH(confirm_orders.created_at) = 10, confirm_orders.payment_total,0)) as Oct,
                    SUM(if(MONTH(confirm_orders.created_at) = 11, confirm_orders.payment_total,0)) as Nov,
                    SUM(if(MONTH(confirm_orders.created_at) = 12, confirm_orders.payment_total,0)) as `Dec`
                FROM confirm_orders
                WHERE YEAR(NOW())) A"))
            ->get();

        //dd($getCartTotal);

        if (Gate::allows('is-all-roles')) {
            return view('index', compact('rmaInfo', 'jobInfo', 'rmaInfoDistri',
                'getCartTotal', 'getRMATotal', 'getConfirmOrder', 'myArray', 'rmaInfoReseller', 'findMonth',
                'getOrderDetail', 'getConfirmOrderMonthly', 'getDataDistributor', 'getTotalSold', 'getRMA',
                'getPieChartData', 'getCustTotalSpend', 'getCustTotalOrder', 'getTotalSalesReseller',
                'getQ1', 'getQ2', 'getQ3', 'getQ4'));
        } else
            return view('invalid-user');
    }

    public function covidIndex()
    {
        return view('covid');
    }

    public function userAddressIndex()
    {
        $userinfo = DB::table('addresses')
            ->where('user_id', Auth::id())
            ->get();

        $countActive = Address::where('default_status', 1)->where('user_id', Auth::id())->count();

        return view('user.address', compact('userinfo', 'countActive'));
    }

    public function userAddressUpdate($id, Request $request)
    {
        $getAddress = Address::find($id);

        if ($getAddress->default_status == 1)
            $getAddress->default_status = 0;
        elseif ($getAddress->default_status == 0)
            $getAddress->default_status = 1;

        $getAddress->save();

        $request->session()->flash('success', 'Address default status updated');

        return response()->json([
            'success' => 'Address default status updated'
        ]);
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
