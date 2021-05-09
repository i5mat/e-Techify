<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RepairController extends Controller
{
    public function RMAIndex()
    {
        $retrieveVal = Product::select('id', 'product_brand', 'product_name')
            ->get();

        $retrieveAddress = Address::all();

        $myArray = array();
        foreach ($retrieveVal as $row) {
            $myArray[] = $row;
        }

        //return response()->json($addressArray);
        return view('RMA.index', compact('myArray', 'retrieveAddress'));
    }

    public function storeRMA(Request $request)
    {
        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('proof_of_purchase_file')) {

            $request->validate([
                'files.*' => 'mimes:jpeg,jpg,png,pdf|max:1024',
            ]);

            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->file('proof_of_purchase_file')->store('rma', 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $rma_req = new Repair([
                "addresses_id" => $request->get('get_add_id_rma'),
                "product_id" => $request->get('prod_name'),
                "user_id" => Auth::id(),
                "sn_no" => $request->get('product_sn_confirm'),
                "date_of_purchase" => $request->get('date-purchased'),
                //"date_of_purchase" => '2021-05-04',
                "file_path" => $request->file('proof_of_purchase_file')->hashName(),
                "reason" => $request->get('reason_field'),
                "status" => 'Pending Checking'
            ]);
            $rma_req->save(); // Finally, save the record.
        }else
            dd('DURIAN TUNGGAL, WE GOT A PROBLEM.');


        return redirect(route('rma.new.request'));
    }

    public function jobSheet($id)
    {
        $findID = Repair::find($id);
        $recipientInfo = DB::table('repairs')
            ->join('addresses', 'repairs.addresses_id', '=', 'addresses.id')
            ->where([
                'repairs.user_id' => Auth::id()
            ])
            ->first();

        $rmaInfo = DB::table('repairs')
            ->join('addresses', 'repairs.addresses_id', '=', 'addresses.id')
            ->join('products', 'repairs.product_id', '=', 'products.id')
            ->where([
                'repairs.user_id' => Auth::id(),
                'repairs.id' => $findID->id
            ])
            ->get();

        return view('rma.rma-sheet', compact('recipientInfo', 'rmaInfo'));
    }
}
