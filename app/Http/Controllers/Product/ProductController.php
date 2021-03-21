<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    // Must have if did not do the Route::resource. This is compulsory for Route::get.
    public function __invoke()
    {
        return view('product.insert');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prod_name' => 'required',
            'prod_sn' => 'required',
            'prod_category' => 'required',
        ]);

        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('prod_image')) {

            $request->validate([
                'image' => 'mimes:jpeg,png' // Only allow .jpg, .bmp and .png file types.
            ]);

            // Save the file locally in the storage/public/ folder under a new folder named /product
            $request->file('prod_image')->store('product', 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            $product = new Product([
                "user_id" => Auth::id(),
                "product_name" => $request->get('prod_name'),
                "product_sn" => $request->get('prod_sn'),
                "product_image_path" => $request->file('prod_image')->hashName(),
                "product_category" => $request->get('prod_category'),
                "product_brand" => $request->get('brandRadio'),
                "product_warranty_duration" => $request->get('warrantyRadio')
            ]);
            $product->save(); // Finally, save the record.
        }

        return dd();
    }
}
