<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $items_to_pay = DB::table('orders')
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Pay'
            ])
            ->get();

        $items_to_ship = DB::table('orders')
            ->join('products', 'products.id', '=', 'orders.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship'
            ])
            ->get();
        return view('order.index', compact('items_to_pay', 'items_to_ship'));
    }
}
