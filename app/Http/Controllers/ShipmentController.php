<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function shipmentIndex()
    {
        $fetchProduct = Product::where('user_id', Auth::id())->get();
        return view('shipment.index', compact('fetchProduct'));
    }

    public function shipmentInsert($id, Request $request)
    {
        $prodID = Product::find($id);

        if (Shipment::where('user_id', '=', Auth::id())->where('status', '=', 'Not Completed')->exists())
        {
            $find_shipment_id = DB::table('shipments')
                ->where('user_id', Auth::id())
                ->where('status', '=', 'Not Completed')
                ->first();

            $shipment_details = new ShipmentDetail([
                "shipment_id" => $find_shipment_id->id,
                "product_id" => $prodID->id,
                "product_order_quantity" => $request->get('prod_qty')
            ]);

            if (ShipmentDetail::where('shipment_id', '=', $find_shipment_id->id)->where('product_id', '=', $prodID->id)->exists())
            {
                $test = ShipmentDetail::where('shipment_id', '=', $find_shipment_id->id)->where('product_id', '=', $prodID->id)->first();
                DB::table('shipment_details')
                    ->where('shipment_id', '=', $find_shipment_id->id)
                    ->where('product_id', '=', $prodID->id)
                    ->increment('product_order_quantity', $request->get('prod_qty'));
            }
            else
                $shipment_details->save();
        }
        else {
            $shipments = new Shipment([
                "user_id" => Auth::id(),
                "status" => 'Not Completed',
                "remark" => $request->get('prod_remark')
            ]);

            $shipments->save();

            $find_shipment_id = DB::table('shipments')
                ->where('user_id', Auth::id())
                ->where('status', '=', 'Not Completed')
                ->first();

            $shipment_details = new ShipmentDetail([
                "shipment_id" => $find_shipment_id->id,
                "product_id" => $prodID->id,
                "product_order_quantity" => $request->get('prod_qty')
            ]);

            $shipment_details->save();
        }

        return response()->json(['success'=>'Inserted!']);
    }
}
