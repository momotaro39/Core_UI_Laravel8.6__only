<?php

namespace App\Http\Controllers\ApiSet;

use Illuminate\Http\Request;


//コントローラーの場所を変えたときには必要になる
use App\Http\Controllers\Controller;


class GitHubApiController extends Controller
{
    public function index()
    {
        return view('GitHubApi.index');
    }
}