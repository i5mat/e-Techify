<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class Profile extends Controller
{
    public function __invoke()
    {
        return view('user.profile');
    }

    public function userDash()
    {
        return view('index');
    }
}
