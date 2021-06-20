<?php


/*
|--------------------------------------------------------------------------
| 一覧表示画面に検索機能をつける 参照先  https://qiita.com/zaburo/items/9fefa3f6834b2e79b734
|--------------------------------------------------------------------------
|
|
*/



/*****************************************
 * メソッド定義
 *
 *
 *****************************************/


一覧表示には通常、検索機能が必要になるので、簡単なサンプルを記載しておきます。



/*****************************************
 * 追加すべき機能
 *
 * index.bladeに検索フォームを追加。
 *
 * index()に検索機能を追加（検索は複数カラムのor,like検索）。
 * 検索結果のページングの際にkeywordを持ちまわるようにする。
 *
 *
 *****************************************/


/*****************************************
 * ビュー
 * index.blade.phpに検索フォームを追加します。
 *
 *
 * ページングしてもkeywordを引き継げるrender()の記述を変更します
 *
 *****************************************/

@section('content')

    <h1>一覧表示</h1>

    <!-- 新規登録ボタン -->
    <div class="row">
        <div class="col-sm-12">
            <a href="/users/create" class="btn btn-primary" style="margin:20px;">新規登録</a>
        </div>
    </div>

    <!-- 検索フォーム -->
    <div class="row">
        <div class="col-sm-12">
            <form method="get" action="/users" class="form-inline" style="margin:20px;">
                <div class="form-group">
                    <label>検索</label>
                    <input type="text" name="keyword" class="form-control" value="{{$keyword}}">
                </div>
                <input type="submit" value="検索" class="btn btn-info">
            </form>
        </div>
    </div>

    <!-- table -->
    <table class="table table-striped">

    <!-- loop -->
    @foreach($users as $user)
        <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td><a href="/users/show/{{$user->id}}" class="btn btn-primary btn-sm">詳細</a></td>
            <td><a href="/users/edit/{{$user->id}}" class="btn btn-primary btn-sm">編集</a></td>
            <td>
                <form method="post" action="/users/destroy/{{$user->id}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="submit" value="削除" class="btn btn-danger btn-sm btn-destroy">
                </form>
            </td>
        </tr>
    @endforeach
    </table>
/*****************************************
 *
 * ページングしてもkeywordを引き継げるrender()
 *
 *
 * {!! users−>appends([′keyword′=>users−>appends([′keyword′=>keyword])->render() !!}
 *
 * 持ち回りする$keywordは、コントローラーから返してもらえる
 *
 *****************************************/
    <!-- page control -->
    {!! $users->appends(['keyword'=>$keyword])->render() !!}

@stop



/*****************************************
 * コントローラー
 * ここでは、検索用のルートやメソッドを新規に定義はせず、index()メソッドを改良します。
 *
 *
 *****************************************/

/*****************************************
 * 全体解説
 *
 * keywordが送られてきているかどうかを判断し、送られていなければ、通常の処理（全検索）を行う。
 * keywordがあれば、where句を追加した検索を行っています。
 * また、keywordの持ち回りのため、送られてきたkeywordを-with('keyword',$keyword)でビューに戻しています。

 *
 *****************************************/

    public function index()
    {
        //キーワード受け取り
        $keyword = \Input::get('keyword');

        //クエリ生成
        $query = User::query();

        //もしキーワードがあったら
        if(!empty($keyword))
        {
            $query->where('email','like','%'.$keyword.'%')->orWhere('name','like','%'.$keyword.'%');
        }

        //ページネーション
        $users = $query->orderBy('id','desc')->paginate(10);
        return view('users.index')->with('users',$users)
                                  ->with('keyword',$keyword);
    }




/*****************************************
 * 部分解説
 * 1つキーワードでemailとnameカラムの両方をorでlike検索しています。
 * 1つのカラムだけに絞りたければ、orWhereを消せばいい。
 *
 *****************************************/
$query->where('email', 'like', '%' . $keyword . '%')->orWhere('name', 'like', '%' . $keyword . '%');



/*****************************************
 * 部分解説
 *
 * また、AND検索をしたい場合
 *
 *****************************************/

$query->where('name','like','%'.$keyword.'%');
$query->where('email','like','%'.$keyword.'%');

// チェーンでつなげても良い
$query->where('name', 'like', '%' . $keyword . '%')->where('email', 'like', '%' . $keyword . '%');
