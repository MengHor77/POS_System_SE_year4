<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class GetStartController extends Controller
{
    public function index()
    {
        return view('app'); 
    }
}
