<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    public function shipmentIndex()
    {
        $fetchProduct = Product::all();
        $getProductBrand = Product::select('product_brand')->distinct()->get();
        $retrieveVal = Product::select('id', 'product_brand', 'product_name', 'product_image_path')
            ->get();

        $getInfo = Shipment::select('remark', 'status', 'id')->first();

        if (Gate::allows('is-reseller')) {
            $getItems = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipment_details.shipment_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id')
                ->where([
                    'shipments.user_id' => Auth::id(),
                    'shipments.status' => 'Requested'
                ])
                ->get();

            $approved = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipments.id AS shipment_id', 'shipments.status')
                ->where([
                    'shipments.user_id' => Auth::id(),
                ])
                ->distinct()
                ->get();

            return view('shipment.index', compact('fetchProduct', 'getProductBrand', 'retrieveVal',
                'getItems', 'getInfo', 'approved'));

        } elseif (Gate::allows('is-distributor')) {
            $getItems = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipment_details.shipment_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id')
                ->where([
                    'products.user_id' => Auth::id(),
                ])
                ->get();

            $approved = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipments.id AS shipment_id', 'shipments.status')
                ->where([
                    'products.user_id' => Auth::id(),
                ])
                ->distinct()
                ->get();

            return view('shipment.index', compact('fetchProduct', 'getProductBrand', 'retrieveVal',
                'getItems', 'getInfo', 'approved'));
        }
    }

    public function shipmentDetailsIndex($id)
    {
        $findID = Shipment::findOrFail($id);

        $resellerInfo = Shipment::where('id', $findID->id)->first();

        if (Gate::allows('is-reseller')) {
            $getItems = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipment_details.shipment_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id')
                ->where([
                    'shipments.user_id' => Auth::id(),
                    'shipments.id' => $findID->id
                ])
                ->get();
        } elseif (Gate::allows('is-distributor')) {
            $getItems = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipment_details.shipment_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id')
                ->where([
                    'products.user_id' => Auth::id(),
                    'shipments.id' => $findID->id
                ])
                ->get();
        }

        return view('shipment.shipmentdetails', compact('getItems', 'resellerInfo'));
    }

    public function shipmentInsert($id, Request $request)
    {
        $prodID = Product::find($id);

        if (Shipment::where('user_id', '=', Auth::id())->where('status', '=', 'Requested')->exists())
        {
            $find_shipment_id = DB::table('shipments')
                ->where('user_id', Auth::id())
                ->where('status', '=', 'Requested')
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
                "status" => 'Requested',
                "remark" => $request->get('prod_remark')
            ]);

            $shipments->save();

            $find_shipment_id = DB::table('shipments')
                ->where('user_id', Auth::id())
                ->where('status', '=', 'Requested')
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

    public function shipmentUpdate($id, Request $request)
    {
        $findID = Shipment::findOrFail($id);
        if ($findID->status == 'Approved') {
            $request->validate([
                'files.*' => 'mimes:jpeg,jpg,png,pdf|max:1024',
            ]);

            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->file('proof_of_payment_file')->store('shipments', 'public');
            $findID->proof_of_payment = $request->file('proof_of_payment_file')->hashName();
            $findID->status = 'Shipped';
            $findID->remark = 'Waiting Shipment from distributor. Tracking number will be updated.';
        } elseif ($findID->status == 'Requested') {
            $findID->status = $request->get('status');
            $findID->remark = $request->get('remark');
        }

        $findID->save();

        return response()->json(['success'=>'Shipment Approved']);
    }

    public function shipmentApprovalDistributor($id, Request $request)
    {
        $findID = Shipment::findOrFail($id);
        $findID->status = $request->get('status');
        $findID->remark = $request->get('remark');
        $findID->tracking_no = $request->get('tracking');

        $findID->save();

        return response()->json(['success'=>'Shipment Approved']);
    }
}
