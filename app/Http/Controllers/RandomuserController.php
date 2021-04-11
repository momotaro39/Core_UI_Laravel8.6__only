<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RandomuserController extends Controller
{
    public function index()
    {
        return view('randomuser.index');
    }
}