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
use Intervention\Image\Facades\Image;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class OrderController extends Controller
{
    public function index()
    {
        $to_ship = Order::where([
            'user_id' => Auth::id(),
            'order_status' => 'To Ship'
        ])
        ->get();

        return view('order.index', compact('to_ship'));
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
            "payment_method" => 'ONLINE BANKING - M2U',
            "tracking_num" => rand(0, 999999999999)
        ]);

        $findOrderID->order_status = 'To Ship';

        $insertData->save();
        $findOrderID->save();

        //dd($insertData, $findOrderID);

        return view('order.thankyou');
    }

    public function airwayBill($id)
    {
        $findID = Order::find($id);

        $recipientInfo = DB::table('confirm_orders')
            ->join('addresses', 'confirm_orders.addresses_id', '=', 'addresses.id')
            ->join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Ship',
                'orders.id' => $findID->id
            ])
            ->first();

        $name = $recipientInfo->name;
        $companyName = 'Xmiryna Technology';
        $companyAddress = 'No. 79 Jalan Taman Melati 1, Taman Melati, Setapak 53100 Kuala Lumpur, Setapak, 53100 Kuala Lumpur';
        $phone = $recipientInfo->phone_no;
        $address = $recipientInfo->address;
        $tracking = $recipientInfo->tracking_num;
        $postcode_recipient = '75450';

//        $qr_code_tracking = new DNS1D();
//        $qr_code_tracking->getBarcodeSVG($recipientInfo->tracking_num, "C39", 1, 50, '#2A3239');

        $qr_code_tracking = (new  DNS1D)->getBarcodeSVG($recipientInfo->tracking_num, "C39", 1, 50, '#2A3239');

        // Sender name.
        $img = Image::make(public_path('awb/airwaybill.jpg'));
        $img->text($companyName, 610, 1130, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Sender address.
        $charactersLimit = 40;
        $yourTextString = $companyAddress;
        $output = wordwrap($yourTextString, $charactersLimit);

        $img->text($output, 810, 1635, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Postcode sender.
        $img->text($postcode_recipient, 435, 1985, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Postcode recipient.
        $img->text($postcode_recipient, 435, 3000, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Recipient address.
        $charactersLimitt = 40;
        $yourTextStringg = $address;
        $output = wordwrap($yourTextStringg, $charactersLimitt);

        $img->text($output, 810, 2655, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Recipient name.
        $img->text($name, 780, 2150, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Big right postcode.
        $img->text($postcode_recipient, 2150, 2540, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(150);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Sender phone no.
        $img->text('+(60) '.$phone, 560, 1210, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Sender phone no.
        $img->text('+(60) '.$phone, 560, 1210, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Tracking no. on right bottom.
        $img->text($tracking, 2130, 3395, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(90);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        $img->text(base64_encode($qr_code_tracking), 2130, 3000, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(40);
            $font->color('#000000');
            $font->align('center');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        return response($img)->header('Content-type','image/png');
    }
}
