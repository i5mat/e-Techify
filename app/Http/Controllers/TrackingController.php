<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    public function trackingIndex($id)
    {
        $findID = Order::find($id);

        $recipientInfo = DB::table('confirm_orders')
            ->join('addresses', 'confirm_orders.addresses_id', '=', 'addresses.id')
            ->join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where([
                'orders.user_id' => $findID->user_id,
                'orders.order_status' => $findID->order_status,
                'orders.id' => $findID->id
            ])
            ->first();

        $trackingStatus = Tracking::where([
            'tracking_no' => $recipientInfo->tracking_num,
            'order_id' => $recipientInfo->order_id
        ])->get();

        return view('track.index', compact('recipientInfo', 'trackingStatus'));
    }

    public function googleTrack($id)
    {
        $findID = Order::findOrFail($id);

        $recipientInfo = DB::table('confirm_orders')
            ->join('addresses', 'confirm_orders.addresses_id', '=', 'addresses.id')
            ->join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where([
                'orders.user_id' => $findID->user_id,
                'orders.order_status' => $findID->order_status,
                'orders.id' => $findID->id
            ])
            ->first();

        return view('track.google-track', compact('recipientInfo'));
    }

    public function insertTracking($id, Request $request)
    {
        $findOrderID = Order::find($id);

        $findTrackDetail = Tracking::join('orders', 'orders.id', '=', 'trackings.order_id')
            ->where([
                'orders.id' => $findOrderID->id
            ])
            ->first();

        $insertTracking = new Tracking([
            "order_id" => $findOrderID->id,
            "tracking_no" => $findTrackDetail->tracking_no,
            "current_status" => $request->input('update_tracking')
        ]);

        if ($request->input('update_tracking') == 'Product Delivered')
        {
            $findOrderID->order_status = 'Delivered';
            $findOrderID->courier_tracking_no = $request->get('courier');
            $findOrderID->save();
            $insertTracking->save();
        }
        else
            $insertTracking->save();

        return response()->json(['success'=>'Status is updated! See the tracking flow!']);

        //return redirect()->back()->with('success', 'Status is updated! See the tracking flow above :)');
        //dd($findTrackDetail->tracking_no);
    }

    public function trackOutParcelIndex()
    {
        return view('track.tracking-parcel');
    }

    public function trackOutParcel(Request $request)
    {
        $trackingStatusHeader = Tracking::where([
            ['tracking_no', 'LIKE', '%' . $request->get('search-track-num') . '%'],
            ['current_status', 'Product Delivered']
        ])->exists();

        $tracking_no = $request->get('search-track-num');
        if (Tracking::where('tracking_no', '=', $tracking_no)->exists()) {
            $trackingStatus = Tracking::where([
                ['tracking_no', 'LIKE', '%' . $request->get('search-track-num') . '%'],
            ])->get();

            return view('track.tracking-parcel-result', compact('trackingStatus', 'tracking_no', 'trackingStatusHeader'));
        }else {
            return view('track.tracking-parcel')->with('errorMsg','Tracking is not exist in the system.');
        }
    }
}
