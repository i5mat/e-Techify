<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shipment;
use App\Models\ShipmentDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ShipmentController extends Controller
{
    public function shipmentIndex()
    {
        $fetchProduct = Product::all();
        $getProductBrand = Product::join('users', 'users.id', '=', 'products.user_id')
            ->select('product_brand', 'user_id', 'name')->distinct()->get();
        $getDistributor = DB::table('role_user')->join('users', 'users.id', '=', 'role_user.user_id')
            ->where('role_user.role_id', 2)->get();
        $retrieveVal = Product::join('users', 'users.id', '=', 'products.user_id')
            ->select('products.id', 'products.product_brand', 'products.product_name', 'products.product_image_path',
                'users.name AS distri_name', 'products.user_id')
            ->get();

        $validateShipment = ShipmentDetail::join('products', 'products.id', '=', 'shipment_details.product_id')
            ->join('users', 'users.id', '=', 'products.user_id')
            ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
            ->select('users.name')
            ->where([
                'status' => 'Requested'
            ])
            ->distinct()
            ->get();

        $getInfo = Shipment::select('remark', 'status', 'id')->first();

        if (Gate::allows('is-reseller')) {
            $getItems = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipment_details.shipment_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
                    'shipment_details.product_id', 'shipment_details.product_order_quantity',
                    'shipment_details.id AS shipment_details_id', 'products.new_product_price')
                ->where([
                    'shipments.user_id' => Auth::id(),
                    'shipments.status' => 'Requested'
                ])
                ->get();

            $shipped = Shipment::where([
                'status' => 'Shipped',
                'user_id' => Auth::id()
            ])->get();

            $approved = Shipment::where([
                'status' => 'Approved',
                'user_id' => Auth::id()
            ])->get();

            $waitingApproval = Shipment::where([
                'status' => 'Waiting Approval',
                'user_id' => Auth::id()
            ])->get();

            $requested = Shipment::where([
                'status' => 'Requested',
                'user_id' => Auth::id()
            ])->get();

            return view('shipment.index', compact('fetchProduct', 'getProductBrand', 'retrieveVal',
                'getItems', 'getInfo', 'approved', 'shipped', 'waitingApproval', 'requested', 'getDistributor', 'validateShipment'));

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
                ->select('shipments.id', 'shipments.status', 'shipments.created_at')
                ->where([
                    'products.user_id' => Auth::id(),
                    'shipments.status' => 'Approved',
                ])
                ->distinct()
                ->get();

            $shipped = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipments.id', 'shipments.status', 'shipments.created_at')
                ->where([
                    'products.user_id' => Auth::id(),
                    'shipments.status' => 'Shipped',
                ])
                ->distinct()
                ->get();

            $waitingApproval = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipments.id', 'shipments.status', 'shipments.created_at')
                ->where([
                    'products.user_id' => Auth::id(),
                    'shipments.status' => 'Waiting Approval',
                ])
                ->distinct()
                ->get();

            $requested = DB::table('shipment_details')
                ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
                ->join('products', 'products.id', '=', 'shipment_details.product_id')
                ->select('shipments.id', 'shipments.status', 'shipments.created_at')
                ->where([
                    'products.user_id' => Auth::id(),
                    'shipments.status' => 'Requested',
                ])
                ->distinct()
                ->get();

            return view('shipment.index', compact('fetchProduct', 'getProductBrand', 'retrieveVal',
                'getItems', 'getInfo', 'approved', 'shipped', 'waitingApproval', 'requested'));
        }

        return view('shipment.index', compact('fetchProduct', 'getProductBrand', 'retrieveVal',
            'getInfo', 'getDistributor', 'validateShipment'));

    }

    public function removeItem($id, Request $request)
    {
        ShipmentDetail::destroy($id);
        $request->session()->flash('success', 'Item is removed from your cart');

        return response()->json([
            'success' => 'Item is removed from list'
        ]);
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
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id', 'products.new_product_price')
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
                    'shipment_details.product_id', 'shipment_details.product_order_quantity', 'shipments.id AS shipment_id', 'products.new_product_price')
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
            $file_name = $request->file('proof_of_payment_file')->store('shipments', 's3');
            Storage::disk('s3')->setVisibility($file_name, 'public');

            //$findID->proof_of_payment = $request->file('proof_of_payment_file')->hashName();
            $findID->proof_of_payment = basename($file_name);
            $findID->status = 'Shipped';
            $findID->remark = 'Waiting Shipment from distributor. Tracking number will be updated.';
        } elseif ($findID->status == 'Requested') {
            $findID->status = $request->get('status');
            $findID->remark = $request->get('remark');
        }

        $findID->created_at = now();
        $findID->save();

        return response()->json(['success'=>'Shipment Approved']);
    }

    public function shipmentApprovalDistributor($id, Request $request)
    {
        $findID = Shipment::findOrFail($id);

        if ($findID->status == 'Shipped') {
            $findID->remark = 'Tracking number updated. '.$request->get('tracking');
            $findID->tracking_no = $request->get('tracking');
        } else {
            $findID->status = $request->get('status');
            $findID->remark = $request->get('remark');
            $findID->tracking_no = $request->get('tracking');
        }

        $findID->save();

        return response()->json(['success'=>'Shipment Approved']);
    }

    public function shipmentSheet($id)
    {
        $findID = Shipment::find($id);

        $items = DB::table('shipment_details')
            ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
            ->join('products', 'products.id', '=', 'shipment_details.product_id')
            ->where([
                'shipments.user_id' => $findID->user_id,
                'shipments.id' => $findID->id
            ])
            ->get();

        $recipientInfo = DB::table('shipment_details')
            ->join('shipments', 'shipments.id', '=', 'shipment_details.shipment_id')
            ->where([
                'shipments.user_id' => $findID->user_id,
                'shipments.id' => $findID->id
            ])
            ->first();

        return view('shipment.shipment-sheet', compact('items', 'recipientInfo'));
    }
}
