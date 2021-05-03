<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function RMAIndex()
    {
        $retrieveVal = Product::select('product_brand', 'product_name')
            ->get();

        $retrieveAddress = Address::all();

        $myArray = array();
        foreach ($retrieveVal as $row) {
            $myArray[] = $row;
        }

        //return response()->json($addressArray);
        return view('RMA.index', compact('myArray', 'retrieveAddress'));
    }
}
