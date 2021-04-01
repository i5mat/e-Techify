<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
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
            'prod_price' => 'required',
            'prod_link' => 'required'
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
                "product_brand" => $request->get('prod_brand'),
                "product_warranty_duration" => $request->get('prod_warranty'),
                "product_price" => $request->get('prod_price'),
                "product_link" => $request->get('prod_link')
            ]);
            $product->save(); // Finally, save the record.
        }

        return redirect(route('product.items.index'));
    }

    public function destroy($id, Request $request)
    {
        Product::destroy($id);
        $request->session()->flash('success', 'Product Deleted');

        return redirect(route('user.userdash'));
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $product->update($request->all());

        $product->product_name = $request->prod_name;

        if($product->save()) {
            $request->session()->flash('success',  $product->product_name.' updated successfully');
        }
        else {
            $request->session()->flash('error', 'Product not updated. There was an error.');
        }

        //return dd($request->all());
        return redirect(route('user.userdash'));
    }
}
