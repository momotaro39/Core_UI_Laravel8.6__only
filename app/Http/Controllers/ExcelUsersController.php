<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;


class ExcelUsersController extends Controller
{
    public function export()
    {

        return Excel::download(new UsersExport, 'users.xlsx');
    }
}