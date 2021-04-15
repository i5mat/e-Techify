<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $find_order_id = DB::table('orders')
            ->where('user_id', Auth::id())
            ->where('order_status', '=', 'To Ship')
            ->first();

        $find_order_id_pay = DB::table('orders')
            ->where('user_id', Auth::id())
            ->where('order_status', '=', 'To Pay')
            ->first();

        $items_to_pay = DB::table('order_details')
            ->select('order_details.order_id AS ord_id', 'products.product_image_path AS img_path',
                'products.product_name AS prod_name', 'products.product_price AS prod_price',
                'order_details.product_order_quantity')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Pay'
            ])
            ->get();

        $items_to_ship = DB::table('order_details')
            ->select('order_details.order_id', 'products.product_image_path',
                'products.product_name', 'products.product_price', 'order_details.product_order_quantity',
                'orders.created_at')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship',
                'order_details.order_id' => $find_order_id->id
            ])
            ->get();

        $total_items = DB::table('order_details')
            ->select('order_details.order_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', '=', $find_order_id->id)
            ->groupBy('order_details.order_id')
            ->sum('products.product_price');

        $total_items_pay = DB::table('order_details')
            ->select('order_details.order_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('order_details.order_id', '=', $find_order_id_pay->id)
            ->groupBy('order_details.order_id')
            ->sum(DB::raw('products.product_price * order_details.product_order_quantity'));

        $test2 = Order::where([
            'user_id' => Auth::id(),
            'order_status' => 'To Ship'
        ])
        ->get();

        $test = DB::table('order_details')
            ->select('order_details.order_id', 'products.product_image_path',
                'products.product_name', 'products.product_price', 'order_details.product_order_quantity',
                'orders.created_at', 'orders.id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship',
                'order_details.order_id' => $find_order_id->id
            ])
            ->get();

        //dd($test);

        return view('order.index', compact('items_to_pay', 'items_to_ship', 'total_items', 'total_items_pay', 'test', 'test2'));
    }
}
