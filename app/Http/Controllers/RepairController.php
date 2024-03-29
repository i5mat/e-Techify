<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function RMAIndex()
    {
        $retrieveVal = Product::select('id', 'product_brand', 'product_name')
            ->get();

        $retrieveAddress = Address::where('user_id', Auth::id())->get();

        $myArray = array();
        foreach ($retrieveVal as $row) {
            $myArray[] = $row;
        }

        $getProductBrand = Product::select('product_brand')->distinct()->get();

        //return response()->json($addressArray);
        return view('RMA.index', compact('myArray', 'retrieveAddress', 'getProductBrand'));
    }

    public function storeRMA(Request $request)
    {
        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('proof_of_purchase_file')) {

            $request->validate([
                'files.*' => 'mimes:jpeg,jpg,png,pdf|max:1024',
            ]);

            // Save the file locally in the storage/public/ folder under a new folder named /product
            $file_name = $request->file('proof_of_purchase_file')->store('rma', 's3');
            Storage::disk('s3')->setVisibility($file_name, 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $rma_req = new Repair([
                "addresses_id" => $request->get('get_add_id_rma'),
                "product_id" => $request->get('prod_name'),
                "user_id" => Auth::id(),
                "sn_no" => $request->get('product_sn_confirm'),
                "date_of_purchase" => $request->get('date-purchased'),
                //"file_path" => $request->file('proof_of_purchase_file')->hashName(),
                "file_path" => basename($file_name),
                "reason" => $request->get('reason_field'),
                "status" => 'Pending Receive'
            ]);
            $rma_req->save(); // Finally, save the record.
        }else
            dd('DURIAN TUNGGAL, WE GOT A PROBLEM.');

        $request->session()->flash('success', 'New RMA request, requested ✔');
        return redirect(route('rma.new.request'));
    }

    public function jobSheet($id)
    {
        $findID = Repair::find($id);
        $recipientInfo = DB::table('repairs')
            ->join('addresses', 'repairs.addresses_id', '=', 'addresses.id')
            ->select('repairs.id', 'addresses.name', 'addresses.address', 'addresses.phone_no', 'repairs.created_at')
            ->where([
                'repairs.id' => $findID->id
            ])
            ->first();

        $rmaInfo = DB::table('repairs')
            ->join('addresses', 'repairs.addresses_id', '=', 'addresses.id')
            ->join('products', 'repairs.product_id', '=', 'products.id')
            ->where([
                'repairs.id' => $findID->id
            ])
            ->get();

        $distriAddress = Address::where('user_id', '=', Auth::id())->where('default_status', '=', 1)->first();

        $userAddress = DB::table('repairs')
            ->join('products', 'repairs.product_id', '=', 'products.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->join('addresses', 'users.id', '=', 'addresses.user_id')
            ->where([
                'repairs.id' => $findID->id
            ])
            ->first();

        //dd($userAddress);

        return view('RMA.rma-sheet', compact('recipientInfo', 'rmaInfo', 'distriAddress', 'userAddress'));
    }

    public function updateRMA($id, Request $request)
    {
        $findID = Repair::findOrFail($id);
        $findID->resolve_solution = $request->get('remark_note');
        $findID->receive_at = $request->get('receive');
        $findID->status = $request->get('rma_status');
        $findID->tracking_no = $request->get('track_no');

        $findID->save();

        return response()->json(['success'=>'yey! RMA UPDATED!']);
    }
}
