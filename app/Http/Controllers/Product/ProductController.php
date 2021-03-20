<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('product.index');
    }

    // Must have if did not do the Route::resource. This is compulsory for Route::get.
    public function __invoke()
    {
        return view('user.profile');
    }
}
