<?php

namespace App\Http\Controllers\Integration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OtherAppController extends Controller
{
    public function index()
    {
        return view('contents.dashboard.index');
    }
}