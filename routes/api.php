<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Models\Band\BandMembers;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/


// ここでコンポーネントに情報を渡す 参照先はこちら https://syachiku.net/vue-bootstrap4-table-laravel/
Route::get('/members', function () {
    $bandMembers = BandMembers::all()->count();
    return BandMembers::paginate($bandMembers);
});