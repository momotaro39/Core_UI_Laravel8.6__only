<?php


/*
|--------------------------------------------------------------------------
| 基本の設定 参照先 https://laraweb.net/practice/2446/
|--------------------------------------------------------------------------
|
| データベースをつなげて、データを取得する方法の整理
|
|
*/

Laravelで開発をしている中で、リレーション先のテーブルのカラムに対して検索をかけたいことが発生しました。


今回は「リレーション先のテーブルに対して検索をかけたい」

    /*****************************************
     *
     *
    ******************************************/


/*
|--------------------------------------------------------------------------
| whereHasを利用 参照先 https://laraweb.net/practice/2446/
|--------------------------------------------------------------------------
|
| 【 構文 】
|
| whereHas($relation, Closure $callback)
|
| リレーション先のテーブルに検索条件を詳細に指定する際に使用するメソッド。
| クロ―ジャーの中にリレーション先のカラムの条件を指定してあげます。

|
*/


/*****************************************
 * コントローラ
 * この方法でもできる
 ******************************************/


public function select(){
    $employees = \App\Employee::whereHas('Dept', function($q){
    $q->where('stairs', 1);
})->get();



    /*****************************************
     * Eloquentモデルの結合を使うのではなく、クエリービルダーのJoin句でもＯＫです。
     * コントローラに以下を設定する方法もありです。
     ******************************************/




$employees = \App\Employee::select()
            ->join('depts','depts.id','=','employees.dept_id')
            ->where('stairs',1)
            ->get();


/*
|--------------------------------------------------------------------------
| 総合設定確認 参照先https://laraweb.net/tutorial/2521/
|--------------------------------------------------------------------------
|
| 複数検索をビュー画面も合わせて設定します。
| 上記の内容がわかれば進めやすいので理解しましょう
|
*/



    /*****************************************
     *
     *
    ******************************************/



    /*
|--------------------------------------------------------------------------
| モデルのリレーション 参照先https://laraweb.net/tutorial/2521/
|--------------------------------------------------------------------------
|
|  最初にやっておかないと進まない
|
|
*/



    /*****************************************
     *  １）モデルの作成
     * 一つの部署に多くの従業員が所属しています。
     *
     * ※部署と従業員の関係は1：多の関係になります。
     *
     ******************************************/



app/Employee

class Employee extends Model
{
  public function dept()
  {
  return $this->belongsTo('App\Dept');
  }
}


app/Dept

class Dept extends Model
{
  public function employees()
  {
  return $this->hasMany('App\Employee');
  }
}


    /*****************************************
     *  ２）コントローラーの作成
     * 検索項目があればチェーンメソッドでwhere句を追加していきます。
     *
     *
     * リレーション先のテーブルのカラムも検索対象になっています。
     *
     * ※この場合、whereHasを使うかjoinを使います。
     *
     *
     ******************************************/


app/Http/Controllers/EmployeeController.php


// Request $req  変数を謎に$reqにしています。
// わかりやすくしてるのかもしれません


public function select(Request $req){



        /*****************************************
         * 値を取得
         * 検索のフォームが複数ある場合はたくさん作っていく。
         * ビュー画面のnameの部分と一致させる
         ******************************************/

$dept_name = $req->input('dept_name');
$pref = $req->input('pref');


        /*****************************************
         * 検索QUERY
         * モデルのデータベースから情報を全部取得する
         * 変数名は「$query」がよく利用されているイメージ
         ******************************************/

$query = Employee::query();

        /*****************************************
         *  結合
         *
         ******************************************/


$query->join('depts', function ($query) use ($req) {$query->on('employees.dept_id', '=', 'depts.id');
});



        /*****************************************
         *  変数があるばあい、部分一致を行っていきます。
         * ない場合は全文検索するためにnotEmptyを使います
         ******************************************/

// もし「部署名（($dept_name）」があれば部分一致を探す
if(!empty($dept_name)){
$query->where('dept_name','like','%'.$dept_name.'%');
}

// もし「都道府県（$pref）」があればあれば部分一致を探す
if(!empty($pref)){
$query->where('address','like','%'.$pref.'%');
}


        /*****************************************
         * ページネーション
         *
         ******************************************/

$employees = $query->paginate(5);

        /*****************************************
         *  ビューへ渡す値を配列に格納
         *  配列にして、ページに返しています。
         * ここはもう少し調べます。
         *
         ******************************************/

$hash = array(
'dept_name' => $dept_name, //pass parameter to pager
'pref' => $pref, //pass parameter to pager

'employees' => $employees, //Eloquent
);

return view('employee.list')->with($hash);
}


  /*****************************************
   *  ３）ビューファイル作成
   * テンプレートファイルを作成します。
   * BootstrapのUIキット「Flat UI」を入れています。
   ******************************************/


layout/master_employee.blade.php



<!DOCTYPE HTML>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <!-- Loading Bootstrap -->
  <link href="{{ url('/') }}/dist/css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<!-- Loading Flat UI -->
  <link href="{{ url('/') }}/dist/css/flat-ui.min.css" rel="stylesheet">

<!--Bootstrap theme(Starter)-->
  <link href="{{ url('/') }}/css/starter-template.css" rel="stylesheet">
  <link rel="shortcut icon" href="{{ url('/') }}/dist/img/favicon.ico">

  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  @yield('styles')
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet"><!-- FontAwesome -->

</head>
<body>

<!--=================================================
Navbar
==================================================-->

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container-fluid">
  <!-- スマートフォンサイズで表示されるメニューボタンとテキスト -->
  <div class="navbar-header">
  <!-- タイトルなどのテキスト -->
  <a class="navbar-brand" href="#">従業員リスト</a>
  </div>
  </div>
</nav>

<div class="container" style="margin-top: 40px; margin-bottom: 40px;">
  @yield('content')
</div><!-- /.container -->

@yield('table')

<footer class="footer">
  <div class="container">
  <p class="text-muted">従業員リスト</p>
  </div>
</footer>



<!-- Bootstrap core JavaScript  -->
<script src="{{ url('/') }}/dist/js/vendor/jquery.min.js"></script>
<script src="{{ url('/') }}/dist/js/vendor/video.js"></script>
<script src="{{ url('/') }}/dist/js/flat-ui.min.js"></script>

<script src="{{ url('/') }}/assets/js/prettify.js"></script>
<script src="{{ url('/') }}/assets/js/application.js"></script>

@yield('scripts')
</body>
</html>



    /*****************************************
     *  子のファイル（上記のテンプレートを継承）を作成
     *
    ******************************************/



employee/list.blade.php


@extends('layouts.master_employee')
@section('title', '従業員リスト')

@section('content')

  <!--  Form  -->
  <form action="{{ route('employee.list') }}" method="get" role="form">
  {!! csrf_field() !!}

  <div class="form-group">
  <label for="number" class="control-label col-xs-2">部署</label>
  <div class="col-xs-10">
  <select name="dept_name" class="form-control select select-primary mbl" data-toggle="select">
  <option value="">全部署</option>
  <option value="総務部" @if($dept_name=='総務部') selected @endif>総務部</option>
  <option value="経理部" @if($dept_name=='経理部') selected @endif>経理部</option>
  <option value="人事部" @if($dept_name=='人事部') selected @endif>人事部</option>
  <option value="開発部" @if($dept_name=='開発部') selected @endif>開発部</option>
  <option value="営業部" @if($dept_name=='営業部') selected @endif>営業部</option>
  </select>
  </div>
  </div>

  <div class="form-group">
  <label for="number" class="control-label col-xs-2">住所</label>
  <div class="col-xs-10">
  <select name="pref" class="form-control select select-primary mbl" data-toggle="select">
  <option value="">全国</option>
  <optgroup label="関東">
  <option value="茨城県" @if($pref=='茨城県') selected @endif>茨城県</option>
  <option value="栃木県" @if($pref=='栃木県') selected @endif>栃木県</option>
  <option value="群馬県" @if($pref=='群馬県') selected @endif>群馬県</option>
  <option value="埼玉県" @if($pref=='埼玉県') selected @endif>埼玉県</option>
  <option value="千葉県" @if($pref=='千葉県') selected @endif>千葉県</option>
  <option value="東京都" @if($pref=='東京都') selected @endif>東京都</option>
  <option value="神奈川県" @if($pref=='神奈川県') selected @endif>神奈川県</option>
  </optgroup>
  <optgroup label="関西">
  <option value="大阪府" @if($pref=='大阪府') selected @endif>大阪府</option>
  <option value="京都府" @if($pref=='京都府') selected @endif>京都府</option>
  <option value="兵庫県" @if($pref=='兵庫県') selected @endif>兵庫県</option>
  <option value="滋賀県" @if($pref=='滋賀県') selected @endif>滋賀県</option>
  <option value="奈良県" @if($pref=='奈良県') selected @endif>奈良県</option>
  <option value="和歌山県" @if($pref=='和歌山県') selected @endif>和歌山県</option>
  </optgroup>
  </select>
  </div>
  </div>

  <div class="form-group">
  <div class="col-xs-offset-2 col-xs-10 text-center">
  <button type="submit" class="btn btn-default">検索</button>
  </div>
  </div>

  </form>

@endsection

@section('table')
  <table class="table table-striped">
  <tr>
  <th>部署</th>
  <th>従業員名</th>
  <th>住所</th>
  <th>メールアドレス</th>
  <th>年齢</th>
  <th>電話番号</th>
  </tr>
  <!-- loop -->
  @foreach($employees as $employee)
  <tr>
  <td>{{$employee->dept_name}}</td>
  <td>{{$employee->name}}</td>
  <td>{{$employee->address}}</td>
  <td>{{$employee->email}}</td>
  <td>{{$employee->old}}</td>
  <td>{{$employee->tel}}</td>
  </tr>
  @endforeach
</table>

<!-- name,emailがあれば、パラメータに含める -->
<div class="paginate text-center">
  {!! $employees->appends(['dept_name'=>$dept_name,'pref'=>$pref])->render() !!}
</div>
@endsection

    /*****************************************
     *  ４）ルーティング
    ******************************************/

routes.php


Route::get('employee/list', [
  'uses' => 'EmployeeController@select',
  'as' => 'employee.list'
]);


/*
|--------------------------------------------------------------------------
| 総合設定確認 参照先https://laraweb.net/tutorial/2521/
|--------------------------------------------------------------------------
|
| 複数検索をビュー画面も合わせて設定します。
| 上記の内容がわかれば進めやすいので理解しましょう
|
*/



    /*****************************************
     *
     *
    ******************************************/


