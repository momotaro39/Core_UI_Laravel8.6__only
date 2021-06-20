<?php

namespace App\Http\Controllers;

use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class TopPageController extends Controller
{
    public function top_page()
    {
        return view('MemberManagement.top_page');
    }
}