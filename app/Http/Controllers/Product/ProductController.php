<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\DistributorProduct;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        //$products = Product::paginate(6);
        //$products = Product::query();

        $products = app(Pipeline::class)
            ->send(Product::query())
            ->through([
                \App\QueryFilters\ProductBrand::class,
                \App\QueryFilters\ProductPrice::class,
                \App\QueryFilters\MaxCount::class,
                \App\QueryFilters\ProductCategory::class
            ])
            ->thenReturn()
            ->paginate(6);

        $getProducts = Product::select('product_brand')->distinct()->get();

        return view('product.index', compact('products', 'getProducts'));
    }

    // Must have if did not do the Route::resource. This is compulsory for Route::get.
    public function __invoke()
    {
        $getProductBrand = Product::join('users', 'users.id', '=', 'products.user_id')
            ->select('product_brand', 'user_id', 'name AS distri_name')->distinct()->get();

        $getProductBrandSpecific = Product::join('users', 'users.id', '=', 'products.user_id')
            ->select('product_brand', 'user_id', 'name AS distri_name')
            ->where('user_id', Auth::id())
            ->distinct()->get();

        $getDistriName = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->where([
                'role_user.role_id' => 2
            ])
            ->get();

        $retrieveVal = Product::select('id', 'product_brand', 'product_name')
            ->get();

        return view('product.insert', compact('getProductBrand', 'retrieveVal', 'getDistriName', 'getProductBrandSpecific'));
    }

    public function manageProductIndex()
    {
        if (Gate::allows('is-distributor')) {
            $products = Product::where('user_id', '=', \Auth::id())->paginate(6);
        } elseif (Gate::allows('is-reseller')) {
            $products = Product::paginate(6);
        }

        return view('product.manage', compact('products'));
    }

    public function fetch_data(Request $request)
    {
        if ($request->ajax())
        {
            if (Gate::allows('is-distributor')) {
                $products = Product::where('user_id', '=', \Auth::id())->paginate(6);
            } elseif (Gate::allows('is-reseller')) {
                $products = Product::paginate(6);
            }

            return view('manageProduct-pagination', compact('products'))->render();
        }
    }

    public function stockManagementIndex()
    {
        $user = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.name', 'users.id', 'roles.name AS role_name', 'users.created_at')
            ->where('role_user.role_id', 2)
            ->get();

        return view('distributor.stock-index', compact('user'));
    }

    public function stockManagementView(Request $request)
    {
        $products = Product::where('user_id', '=', $request->route('id'))->paginate(6);

        $user = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.name', 'users.id', 'roles.name AS role_name')
            ->where('role_user.role_id', 2)
            ->where('users.id', $request->route('id'))
            ->first();
        //dd($user);

        return view('distributor.view-stock', compact('products', 'user'));
    }

    public function manageCartIndex()
    {
        $items = DB::table('order_details')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->select('order_details.order_id', 'products.product_image_path', 'products.product_name', 'products.product_price',
            'order_details.product_id', 'order_details.product_order_quantity', 'order_details.id AS o_d_id', 'products.product_stock_count')
            ->where([
                'orders.user_id' => Auth::id(),
                'orders.order_status' => 'To Pay'
            ])
            ->get();

        $myArrays = array();
        foreach ($items as $row) {
            $myArrays[] = $row;
        }

        $userinfo = DB::table('addresses')
            ->where([
                'user_id' => Auth::id(),
                'default_status' => 1
            ])
            ->get();
        return view('product.cart', compact('items', 'userinfo', 'myArrays'));
    }

    public function delItemCart($id, Request $request)
    {
        OrderDetail::destroy($id);
        $request->session()->flash('success', 'Item is removed from your cart');

        return response()->json([
            'success' => 'Record deleted successfully!'
        ]);
    }

    public function addToCart($id, Request $request)
    {
        $prodCart = Product::find($id);

        if (Order::where('user_id', '=', Auth::id())->where('order_status', '=', 'To Pay')->exists())
        {
            $find_order_id = DB::table('orders')
                ->where('user_id', Auth::id())
                ->where('order_status', '=', 'To Pay')
                ->first();

            $order_details = new OrderDetail([
                "order_id" => $find_order_id->id,
                "product_id" => $prodCart->id,
            ]);

            if (OrderDetail::where('order_id', '=', $find_order_id->id)->where('product_id', '=', $prodCart->id)->exists())
            {
                $test = OrderDetail::where('order_id', '=', $find_order_id->id)->where('product_id', '=', $prodCart->id)->first();
                DB::table('order_details')
                    ->where('order_id', '=', $find_order_id->id)
                    ->where('product_id', '=', $prodCart->id)
                    ->increment('product_order_quantity', 1);
            }
            else
                $order_details->save();
        }
        else
        {
            $orders = new Order([
                "user_id" => Auth::id(),
                "order_status" => 'To Pay',
            ]);

            $orders->save();

            $find_order_id = DB::table('orders')
                ->where('user_id', Auth::id())
                ->where('order_status', '=', 'To Pay')
                ->first();

            $order_details = new OrderDetail([
                "order_id" => $find_order_id->id,
                "product_id" => $prodCart->id,
            ]);

            $order_details->save();
        }

        $request->session()->flash('success', 'You have added '.$prodCart->product_name. ' to your cart!');

        return redirect()->back();
    }

    public function store(Request $request)
    {
        //dd($request->all());

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
            $file_name = $request->file('prod_image')->store('product', 's3');
            Storage::disk('s3')->setVisibility($file_name, 'public');

            // Store the record, using the new file hashname which will be it's new filename identity.
            if (Gate::allows('is-distributor')) {
                if ($request->get('new_prod_brand') != null) {
                    $product = new Product([
                        "user_id" => Auth::id(),
                        "product_name" => $request->get('prod_name'),
                        "product_sn" => $request->get('prod_sn'),
                        "product_image_path" => $request->file('prod_image')->hashName(),
                        "product_category" => $request->get('prod_category'),
                        "product_brand" => $request->get('new_prod_brand'),
                        "product_warranty_duration" => $request->get('prod_warranty'),
                        "product_price" => $request->get('prod_price'),
                        "new_product_price" => $request->get('prod_price'),
                        "product_link" => $request->get('prod_link'),
                        "product_stock_count" => $request->get('prod_stock')
                    ]);
                }
                else {
                    $product = new Product([
                        "user_id" => Auth::id(),
                        "product_name" => $request->get('prod_name'),
                        "product_sn" => $request->get('prod_sn'),
                        "product_image_path" => $request->file('prod_image')->hashName(),
                        "product_category" => $request->get('prod_category'),
                        "product_brand" => $request->get('prod_brand'),
                        "product_warranty_duration" => $request->get('prod_warranty'),
                        "product_price" => $request->get('prod_price'),
                        "new_product_price" => $request->get('prod_price'),
                        "product_link" => $request->get('prod_link'),
                        "product_stock_count" => $request->get('prod_stock')
                    ]);
                }
            } elseif (Gate::allows('is-reseller')) {
                if ($request->get('new_prod_brand') != null) {
                    $product = new Product([
                        "user_id" => $request->get('insert_brand_dist'),
                        "product_name" => $request->get('prod_name'),
                        "product_sn" => $request->get('prod_sn'),
                        //"product_image_path" => $request->file('prod_image')->hashName(),
                        "product_image_path" => basename($file_name),
                        "product_category" => $request->get('prod_category'),
                        "product_brand" => $request->get('new_prod_brand'),
                        "product_warranty_duration" => $request->get('prod_warranty'),
                        "product_price" => $request->get('prod_price'),
                        "new_product_price" => $request->get('prod_price'),
                        "product_link" => $request->get('prod_link'),
                        "product_stock_count" => $request->get('prod_stock')
                    ]);

                    //dd($product);
                }
                else {
                    $product = new Product([
                        "user_id" => $request->get('insert_brand_dist'),
                        "product_name" => $request->get('prod_name'),
                        "product_sn" => $request->get('prod_sn'),
                        "product_image_path" => $request->file('prod_image')->hashName(),
                        "product_category" => $request->get('prod_category'),
                        "product_brand" => $request->get('prod_brand'),
                        "product_warranty_duration" => $request->get('prod_warranty'),
                        "product_price" => $request->get('prod_price'),
                        "new_product_price" => $request->get('prod_price'),
                        "product_link" => $request->get('prod_link'),
                        "product_stock_count" => $request->get('prod_stock')
                    ]);
                }
            }

            $product->save(); // Finally, save the record.
        }

        $request->session()->flash('success', 'Product Inserted!');
        return redirect()->back();
    }

    public function destroy($id, Request $request)
    {
        Product::destroy($id);
        $request->session()->flash('success', 'Product Deleted');

        return redirect(route('user.userdash'));
    }

    public function removeCart($id)
    {
        $findID = Order::find($id);
        dd($findID);
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        $product->update($request->all());

        if ($request->has('prod_image_update')) {

            \File::delete(public_path('/storage/product/'.$product->product_image_path));

            $request->validate([
                'files.*' => 'mimes:jpeg,jpg,png',
            ]);

            $request->file('prod_image_update')->store('product', 'public');

            $product->product_name = $request->prod_name;
            $product->product_sn = $request->prod_sn;
            $product->product_image_path = $request->file('prod_image_update')->hashName();
            $product->product_stock_count = $request->prod_stock;
            $product->product_price = $request->prod_price;

        } else {
            $product->product_name = $request->prod_name;
            $product->product_sn = $request->prod_sn;
            $product->product_stock_count = $request->prod_stock;
            $product->product_price = $request->prod_price;
            $product->new_product_price = $request->new_prod_price;
        }

        if($product->save()) {
            $request->session()->flash('success',  $product->product_name.' updated successfully');
        }
        else {
            $request->session()->flash('error', 'Product not updated. There was an error.');
        }

        //return dd($request->all());
        return redirect()->back();
    }

    public function updateStatusSNProduct($id, Request $request)
    {
        $findID = DistributorProduct::findOrFail($id);
        if ($findID->status == 'Occupied')
            $findID->status = 'Not Occupied';
        elseif ($findID->status == 'Not Occupied')
            $findID->status = 'Occupied';

        $findID->save();
        $request->session()->flash('success', 'Product SN is altered 🎈');

        return redirect()->back();
    }

    public function distriInsertProductIndex()
    {
        $uid = Auth::id();

        if (Gate::allows('is-distributor')) {
            $fetchProduct = Product::where('user_id', Auth::id())->get();

            $fetchProductJoin = DistributorProduct::join('products', 'products.id', '=', 'distributor_products.product_id')
                ->select('distributor_products.created_at', 'products.product_name', 'distributor_products.batch_no',
                    'distributor_products.serial_number', 'distributor_products.status', 'distributor_products.product_id', 'distributor_products.id')
                ->where([
                    'distributor_products.user_id' => Auth::id()
                ])
                ->get();

            $getBatchQuarter = DistributorProduct::select(DB::raw('A.batch_no, GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar, ", ", A.Apr, ", ", A.May, ", ", A.Jun, ", ", A.Jul, ", ", A.Aug, ", ", A.Sep, ", ", A.Oct, ", ", A.Nov, ", ", A.Dec) AS Q_Batch'))
                ->from(DB::raw("(SELECT distributor_products.batch_no,
                        SUM(if(MONTH(distributor_products.created_at) = 1, 1,0)) as Jan,
                        SUM(if(MONTH(distributor_products.created_at) = 2, 1,0)) as Feb,
                        SUM(if(MONTH(distributor_products.created_at) = 3, 1,0)) as Mar,
                        SUM(if(MONTH(distributor_products.created_at) = 4, 1,0)) as Apr,
                        SUM(if(MONTH(distributor_products.created_at) = 5, 1,0)) as May,
                        SUM(if(MONTH(distributor_products.created_at) = 6, 1,0)) as Jun,
                        SUM(if(MONTH(distributor_products.created_at) = 7, 1,0)) as Jul,
                        SUM(if(MONTH(distributor_products.created_at) = 8, 1,0)) as Aug,
                        SUM(if(MONTH(distributor_products.created_at) = 9, 1,0)) as Sep,
                        SUM(if(MONTH(distributor_products.created_at) = 10, 1,0)) as Oct,
                        SUM(if(MONTH(distributor_products.created_at) = 11, 1,0)) as Nov,
                        SUM(if(MONTH(distributor_products.created_at) = 12, 1,0)) as `Dec`
                    FROM distributor_products
                    WHERE YEAR(NOW()) AND distributor_products.user_id = '$uid' GROUP BY distributor_products.batch_no) A"))
                ->groupBy('A.batch_no')
                ->get();

        } elseif (Gate::allows('is-reseller')) {
            $fetchProduct = Product::all();

            $fetchProductJoin = DistributorProduct::join('products', 'products.id', '=', 'distributor_products.product_id')
                ->select('distributor_products.created_at', 'products.product_name', 'distributor_products.batch_no',
                    'distributor_products.serial_number', 'distributor_products.status', 'distributor_products.product_id',
                    'distributor_products.id', 'products.user_id')
                ->get();

            $getBatchQuarter = DistributorProduct::select(DB::raw('A.batch_no, GROUP_CONCAT(A.Jan, ", ", A.Feb, ", ", A.Mar, ", ", A.Apr, ", ", A.May, ", ", A.Jun, ", ", A.Jul, ", ", A.Aug, ", ", A.Sep, ", ", A.Oct, ", ", A.Nov, ", ", A.Dec) AS Q_Batch'))
                ->from(DB::raw("(SELECT distributor_products.batch_no,
                        SUM(if(MONTH(distributor_products.created_at) = 1, 1,0)) as Jan,
                        SUM(if(MONTH(distributor_products.created_at) = 2, 1,0)) as Feb,
                        SUM(if(MONTH(distributor_products.created_at) = 3, 1,0)) as Mar,
                        SUM(if(MONTH(distributor_products.created_at) = 4, 1,0)) as Apr,
                        SUM(if(MONTH(distributor_products.created_at) = 5, 1,0)) as May,
                        SUM(if(MONTH(distributor_products.created_at) = 6, 1,0)) as Jun,
                        SUM(if(MONTH(distributor_products.created_at) = 7, 1,0)) as Jul,
                        SUM(if(MONTH(distributor_products.created_at) = 8, 1,0)) as Aug,
                        SUM(if(MONTH(distributor_products.created_at) = 9, 1,0)) as Sep,
                        SUM(if(MONTH(distributor_products.created_at) = 10, 1,0)) as Oct,
                        SUM(if(MONTH(distributor_products.created_at) = 11, 1,0)) as Nov,
                        SUM(if(MONTH(distributor_products.created_at) = 12, 1,0)) as `Dec`
                    FROM distributor_products
                    WHERE YEAR(NOW()) GROUP BY distributor_products.batch_no) A"))
                ->groupBy('A.batch_no')
                ->get();
        }

        return view('distributor.insertproduct', compact('fetchProduct', 'fetchProductJoin',
            'getBatchQuarter'));
    }

    public function distriInsertSN(Request $request)
    {
        $insertData = new DistributorProduct([
            "product_id" => $request->input('products_id_dist'),
            "user_id" => $request->input('distri_id'),
            "batch_no" => $request->get('floatingSelectBatch'),
            "status" => 'Not Occupied',
            "serial_number" => $request->input('insert_product_sn')
        ]);
        $insertData->save();

        //dd($insertData);
        return response()->json(['success'=>'Inserted!']);
    }
}
