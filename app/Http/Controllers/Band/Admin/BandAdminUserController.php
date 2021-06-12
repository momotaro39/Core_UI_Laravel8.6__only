<?php

namespace App\Http\Controllers\Band\Admin;

/*
    |--------------------------------------------------------------------------
    |
    | 追加機能
    |
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

// ページネーションを使う時に利用
use Illuminate\Pagination\Paginator;

//コントローラーの場所を変えたときには必要になる
use App\Http\Controllers\Controller;

//認証機能を使うときに必要
use Illuminate\Support\Facades\Auth;



use App\Models\Band\UserRole;
use Illuminate\Http\Request;

class BandAdminUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        $user = auth()->user();
        $this->authorize('viewAny', $user);  // Policy をチェック ポリシーを作成後コメントイン


        $users = \App\Models\User::get(); // 管理者と代表者の一覧を取得

        return view('MemberManagement.users.index', compact('users')); // users.index.blade を呼出し、 usersを渡す

    }
}
