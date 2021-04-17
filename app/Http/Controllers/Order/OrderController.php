<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ConfirmOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $test2 = Order::where([
            'user_id' => Auth::id(),
            'order_status' => 'To Ship'
        ])
        ->get();

        return view('order.index', compact('test2'));
    }

    public function orderDetailsIndex($id)
    {
        $findID = Order::find($id);

        $orderInfo = DB::table('order_details')
            ->select('order_details.order_id', 'products.product_image_path',
                'products.product_name', 'products.product_price', 'order_details.product_order_quantity',
                'orders.created_at', 'orders.id AS orders')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship',
                'order_details.order_id' => $findID->id
            ])
            ->get();

        $total_items = DB::table('order_details')
            ->select('order_details.order_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', '=', $findID->id)
            ->groupBy('order_details.order_id')
            ->sum(DB::raw('products.product_price * order_details.product_order_quantity'));

        $recipientInfo = DB::table('confirm_orders')
            ->join('addresses', 'confirm_orders.addresses_id', '=', 'addresses.id')
            ->join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship',
                'orders.id' => $findID->id
            ])
            ->first();

        //dd($recipientInfo);

        return view('order.orderdetails', compact('orderInfo', 'total_items', 'recipientInfo'));
    }

    public function thankYouIndex()
    {
        return view('order.thankyou');
    }

    public function orderConfirm($id, Request $request)
    {
        $findOrderID = Order::find($id);

        $insertData = new ConfirmOrder([
            "order_id" => $findOrderID->id,
            "addresses_id" => $request->get('get_add_id'),
            "payment_total" => $request->get('tot'),
            "payment_method" => 'TEST'
        ]);

        $findOrderID->order_status = 'To Ship';

//        $insertData->save();
//        $findOrderID->save();

        dd($insertData, $findOrderID);
    }
}
