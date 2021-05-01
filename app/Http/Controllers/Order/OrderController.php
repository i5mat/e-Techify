<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ConfirmOrder;
use App\Models\DistributorProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Tracking;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Symfony\Component\Console\Input\Input;

class OrderController extends Controller
{
    public function index()
    {
        $to_ship = Order::where([
            'user_id' => Auth::id(),
            'order_status' => 'To Ship'
        ])
        ->get();

        $delivered = Order::where([
            'user_id' => Auth::id(),
            'order_status' => 'Delivered'
        ])
        ->get();

        return view('order.index', compact('to_ship', 'delivered'));
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
                'orders.order_status' => $findID->order_status,
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
                'orders.order_status' => $findID->order_status,
                'orders.id' => $findID->id
            ])
            ->first();

        //dd($recipientInfo);

        return view('order.orderdetails', compact('orderInfo', 'total_items', 'recipientInfo'));
    }

    public function receiptIndex($id)
    {
        $findID = Order::find($id);

        $orderInfo = DB::table('order_details')
            ->select('order_details.order_id', 'products.product_image_path',
                'products.product_name', 'products.product_price', 'order_details.product_order_quantity',
                'orders.created_at', 'orders.id AS orders', 'products.product_sn', 'products.product_price',
                'products.product_warranty_duration', 'order_details.serial_number')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => $findID->order_status,
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
                'orders.order_status' => $findID->order_status,
                'orders.id' => $findID->id
            ])
            ->first();

        $charactersLimitt = 40;
        $yourTextStringg = $recipientInfo->address;
        $output = wordwrap($yourTextStringg, $charactersLimitt);

        //view()->share('p', $orderInfo, $recipientInfo, $total_items, $output);
        //$pdf_doc = PDF::loadView('receipt.index', compact('orderInfo', 'total_items', 'recipientInfo', 'output'));
        //$pdf_doc->download('pdf.pdf');
        //return $pdf_doc->stream();

        return view('receipt.index', compact('orderInfo', 'total_items', 'recipientInfo', 'output'));
    }

    public function addProductSN($id)
    {
        $findID = Order::find($id);

        $orderInfo = DB::table('order_details')
            ->select('order_details.order_id', 'products.product_image_path',
                'products.product_name', 'products.product_price', 'order_details.product_order_quantity',
                'orders.created_at', 'orders.id AS orders', 'products.product_sn', 'products.product_price',
                'products.product_warranty_duration', 'order_details.serial_number', 'order_details.product_id', 'order_details.updated_at')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => $findID->order_status,
                'order_details.order_id' => $findID->id
            ])
            ->get();

        foreach ($orderInfo as $key => $data)
        {
            $findIDProduct = Product::where('id', $data);

            //dd($data->product_id);

            //dd($findIDProduct);

            $findSN = DistributorProduct::join('order_details', 'order_details.product_id', '=', 'distributor_products.product_id')
                ->select('distributor_products.serial_number', 'products.product_name')
                ->join('products', 'products.id', '=', 'order_details.product_id')
                ->where([
                    'distributor_products.status' => 'Not Occupied',
                    'order_details.order_id' => $findID->id
                ])
                ->get();
        }

        //dd($findSN);

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
                'orders.order_status' => $findID->order_status,
                'orders.id' => $findID->id
            ])
            ->first();

        $updaterecs = OrderDetail::where('order_id', '=', $findID->id)
            ->where('product_id', '=', $data->product_id)
            ->first();

        // use of explode
        $string = $updaterecs->serial_number;
        $str_arr = array_pad(explode(', ', $string), $updaterecs->product_order_quantity, null);

        return view('order.insertsn', compact('orderInfo', 'total_items', 'recipientInfo', 'findSN', 'str_arr'));
    }

    public function updateProductSN($id, Request $request)
    {
        $findID = Order::find($id);
        //$data = $request->except(['_token']);

        if ($request->input('getproduct_qty') == 1)
        {
            foreach ($request->except(['_token']) as $key => $data)
            {
                //dump("key => " . $key . " data => " . $data);
                $updaterec = OrderDetail::where('order_id', '=', $findID->id)
                    ->where('product_id', '=', $key)
                    ->update(['serial_number' => $data]);

                $updateDist = DistributorProduct::where('product_id', '=', $key)
                    ->where('serial_number', '=', $data)
                    ->update(['status' => 'Occupied']);
            }
        }
        elseif ($request->input('getproduct_qty') > 1)
        {
            $input = $request->only(['product_sn']);
            $items = array(['product_sn']);
            foreach($input as $inputs) {
                $items[] = $inputs;

                $json = json_encode($inputs);
                $jsonobj = json_decode($json);

            }
            //dd($jsonobj);
            $result = implode(', ', $jsonobj);

            $updaterec = OrderDetail::where('order_id', '=', $findID->id)
                ->where('product_id', '=', $request->input('getproduct_sn'))
                ->update(['serial_number' => $result]);

//        if (in_array('ismat3', $request->only(['product_sn']))) {
//            dd('TRUE');
//        }
//        else
//            dd($items[3]);

            //dd($inputs);

//        foreach ($inputs as $url) {
//            if (strpos($result, $url) !== FALSE)
//            {
//                $updateDist = DistributorProduct::where('product_id', '=', $request->input('getproduct_sn'))
//                    ->where(['serial_number', '=', $url])
//                    ->update(['status' => 'Occupied']);
//
//                echo "Match found";
//                return true;
//            }
//        }
//        echo "Not found!";
//        return false;

            foreach ($inputs as $key => $data)
            {
                //dump("key => " . $request->input('getproduct_sn') . " data => " . $data);

                $updateDist = DistributorProduct::where('product_id', '=', $request->input('getproduct_sn'))
                    ->where('serial_number', '=', $data)
                    ->update(['status' => 'Occupied']);
            }

//        if (stripos(json_encode($items[3]), $result) !== false) {
//            dd('TRUE');
//        }
//        else
//        {
//            //dd(json_encode($items[3]));
//            //dd($items[3]);
//            dd($request->only(['product_sn']), json_encode($items[3]), $items[3]);
//        }


            //dd($json);
        }

        return redirect()->back()->with('success', 'SUCCESS BAH');
    }

    public function thankYouIndex()
    {
        return view('order.thankyou');
    }

    public function orderConfirm($id, Request $request)
    {
        $findOrderID = Order::find($id);
        $randomNum = rand(0, 999999999999);
        $randomReceiptNum = "XT-".rand(0, 999999999999);

        $insertData = new ConfirmOrder([
            "order_id" => $findOrderID->id,
            "addresses_id" => $request->get('get_add_id'),
            "payment_total" => $request->get('tot'),
            "payment_method" => 'ONLINE BANKING - M2U',
            "tracking_num" => $randomNum,
            "receipt_no" => $randomReceiptNum
        ]);

        Product::where('id', '=', $request->input('get_prod_id'))
            ->decrement('product_stock_count', $request->input('qty_prod'));

        OrderDetail::where('product_id', '=', $request->input('get_prod_id'))
            ->update(['product_order_quantity' => $request->input('qty_prod')]);

        $findOrderID->order_status = 'To Ship';

        $insertData->save();
        $findOrderID->save();

        $insertTracking = new Tracking([
            "order_id" => $findOrderID->id,
            "tracking_no" => $insertData->tracking_num,
            "current_status" => "Confirmed Order"
        ]);

        $insertTracking->save();

        //dd($insertData, $findOrderID, $insertTracking);

        return view('order.thankyou');
    }

    public function cancelOrder($id, Request $request)
    {
        Order::destroy($id);

        return redirect()->back()->with('success', 'Order deleted...');
    }

    public function airwayBill($id)
    {
        $findID = Order::find($id);

        $recipientInfo = DB::table('confirm_orders')
            ->join('addresses', 'confirm_orders.addresses_id', '=', 'addresses.id')
            ->join('orders', 'orders.id', '=', 'confirm_orders.order_id')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => $findID->order_status,
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
        $shipByDate = date('d M Y h:i A');
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
            $font->size(180);
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
