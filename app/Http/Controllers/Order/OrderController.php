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
        $randomNum = rand(0, 999999999999);

        $insertData = new ConfirmOrder([
            "order_id" => $findOrderID->id,
            "addresses_id" => $request->get('get_add_id'),
            "payment_total" => $request->get('tot'),
            "payment_method" => 'ONLINE BANKING - M2U',
            "tracking_num" => $randomNum
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
        $companyPostcode = '53100';
        $companyPhoneNo = '172178319';
        $phone = $recipientInfo->phone_no;
        $address = $recipientInfo->address;
        $tracking = $recipientInfo->tracking_num;
        $postcode_recipient = $recipientInfo->postcode;
        $orderID = $recipientInfo->order_id;
        $shipByDate = date('d-M-Y H:i A');
        $weightItem = '10 KG';

        $bar_code_tracking = (new DNS1D)->getBarcodePNG($tracking, "C39", 1, 50, array(1,1,1));
        $qr_code_tracking = (new DNS2D)->getBarcodePNG('0'.$phone, "QRCODE", 5, 5, array(1,1,1));

        // Sender name.
        $img = Image::make(public_path('awb/airwaybill.jpg'));
        $img->text($companyName, 350, 1130, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Ship By Date
        $img->text($shipByDate, 450, 760, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Weight
        $img->text($weightItem, 450, 835, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Order ID
        $img->text($orderID, 450, 920, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Sender address.
        $charactersLimit = 50;
        $yourTextString = $companyAddress;
        $output = wordwrap($yourTextString, $charactersLimit);

        $img->text($output, 350, 1425, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Postcode sender.
        $img->text($companyPostcode, 435, 1985, function($font) {
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

        $img->text($output, 350, 2445, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Recipient name.
        $img->text($name, 350, 2095, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
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
        $img->text('+(60) '.$companyPhoneNo, 350, 1160, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
            $font->valign('top');
            $font->angle(360);
        });
        $img->save(public_path('awb/test-awb.jpg'));

        // Recipient phone no.
        $img->text('+(60) '.$phone, 350, 2225, function($font) {
            $font->file(public_path('awb/MYRIADPRO-REGULAR.ttf'));
            $font->size(60);
            $font->color('#000000');
            $font->align('left');
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

        \Storage::disk('public')->put('test'.$tracking.'.png', base64_decode($bar_code_tracking));
        \Storage::disk('public')->put('lol'.$tracking.'.png', base64_decode($qr_code_tracking));

        $barcode = Image::make('storage/test'.$tracking.'.png')->resize(1300, 200);
        $qrcode = Image::make('storage/lol'.$tracking.'.png')->resize(350, 350);
        $img->insert($barcode, 'top-left', 550, 350);
        $img->insert($qrcode, 'top-left', 1960, 2900);
        $img->save(public_path('awb/test-awb.jpg'));

        return response($img)->header('Content-type','image/png');
    }
}
