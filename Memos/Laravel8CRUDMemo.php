<?php


/*
|--------------------------------------------------------------------------
| 管理画面でCRUD機能作成編  参照先  https://www.webopixel.net/php/1668.html
|--------------------------------------------------------------------------
|
| Github
| https://github.com/k-ishiwata/LaravelMiniCMS2020
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

Postコントローラーの雛形を作成
管理画面のコントローラーから作っていきます。
makeコマンドで雛形を作りましょう。

$ php artisan make:controller Back/PostController -r


/********************************************
 * メソッド定義
 * ******************************************/
ルーターの設定
作成したコントローラーにアクセスできるようにルーターの設定をします。
フロントではindexとshowしか使用しなかったのでonlyで限定しましたが、管理画面ではshowだけ使用しないのでexceptで指定します。

routes/back.php
?


Route::resource('posts', 'PostController')->except('show');


/********************************************
 * メソッド定義
 * ******************************************/
一覧画面の作成（index）

コントローラーはフロント画面とほとんど同じです。

Http/Controllers/Back/PostController.php
?


namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * 一覧画面
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $posts = Post::latest('id')->paginate(20);
        return view('back.posts.index', compact('posts'));
    }

    // ...
}


/********************************************
 * メソッド定義
 * ******************************************/
一覧のビューです。
新規・編集・削除に繊維するボタンがあるだけでここもほとんどフロントと変わらないですね。
削除は画面がないので、formを埋め込んでそのまま削除できるようにしています。

resources/views/back/posts/index.blade.php
?



<?php
$title = '投稿一覧';
?>
@extends('back.layouts.base')

@section('content')
<div class="card-header">投稿一覧</div>
<div class="card-body">
    {{ link_to_route('back.posts.create', '新規登録', null, ['class' => 'btn btn-primary mb-3']) }}
    @if(0 < $posts->count())
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">タイトル</th>
                    <th scope="col" style="width: 4.3em">状態</th>
                    <th scope="col" style="width: 9em">公開日</th>
                    <th scope="col" style="width: 12em">編集</th>
                </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->is_public_label }}</td>
                    <td>{{ $post->published_format }}</td>
                    <td class="d-flex justify-content-center">
                        {{ link_to_route('front.posts.show', '詳細', $post, [
                            'class' => 'btn btn-secondary btn-sm m-1',
                            'target' => '_blank'
                        ]) }}
                        {{ link_to_route('back.posts.edit', '編集', $post, [
                            'class' => 'btn btn-secondary btn-sm m-1'
                        ]) }}
                        {{ Form::model($post, [
                            'route' => ['back.posts.destroy', $post],
                            'method' => 'delete'
                        ]) }}
                            {{ Form::submit('削除', [
                                'onclick' => "return confirm('本当に削除しますか?')",
                                'class' => 'btn btn-danger btn-sm m-1'
                            ]) }}
                        {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

/********************************************
 * メソッド定義
 * ******************************************/

新規登録画面（create）
新規登録画面は入力フォームを登録するだけのでコントローラーの処理はないです。

Http/Controllers/Back/PostController.php
?


public function create()
{
    return view('back.posts.create');
}

フォームは登録と編集共通で同じになるので、共通のファイルとして作ります。

Bootstrapは隣接要素にis-invalidクラスを指定しないとエラーを表示しないところがややこしいんですよね。
チェックボックスとか見た目に関してはある程度妥協していきます。

resources/views/back/posts/_form.blade.php
?



<div class="form-group row">
    {{ Form::label('title', 'タイトル', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-10">
        {{ Form::text('title', null, [
            'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
            'required'
        ]) }}
        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('body', '内容', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-10">
        {{ Form::textarea('body', null, [
            'class' => 'form-control' . ($errors->has('body') ? ' is-invalid' : ''),
            'rows' => 5
        ]) }}
        @error('body')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="form-group row">
    {{ Form::label('is_public', 'ステータス', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-10">
        @foreach([1 => '公開', 0 => '非公開'] as $key => $value)
            <div class="form-check form-check-inline">
                {{ Form::radio('is_public', $key, null, [
                    'id' => 'is_public'.$key,
                    'class' => 'form-check-input' . ($errors->has('is_public') ? ' is-invalid' : '')
                ]) }}
                {{ Form::label('is_public'.$key, $value, ['class' => 'form-check-label']) }}
                @if($key === 0)
                    @error('is_public')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="form-group row">
    {{ Form::label('published_at', '公開日', ['class' => 'col-sm-2 col-form-label']) }}
    <div class="col-sm-10">
        {{ Form::datetime('published_at',
            isset($post->published_at)
                ? $post->published_at->format('Y-m-d H:i')
                : now()->format('Y-m-d H:i'),
        [
            'class' => 'form-control' . ($errors->has('published_at') ? ' is-invalid' : '')
        ]) }}
        @error('published_at')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-10">
        <button type="submit" class="btn btn-primary">保存</button>
        {{ link_to_route('back.posts.index', '一覧へ戻る', null, ['class' => 'btn btn-secondary']) }}
    </div>
</div>
コントローラーから読み込むcreate.blade.phpで先ほどの_form.blade.phpをインクルードします。

resources/views/back/posts/create.blade.php
?



<?php
$title = '投稿登録';
?>
@extends('back.layouts.base')

@section('content')
    <div class="card-header">{{ $title }}</div>
    <div class="card-body">
        {{ Form::open(['route' => 'back.posts.store']) }}
        @include('back.posts._form')
        {{ Form::close() }}
    </div>
@endsection


/********************************************
 * バリデーションの作成
 * createからstoreにデータをポストして保存するのですが、そのまま保存しようとするとDBに保存できない値の場合エラーで止まってしいます。
 * そのようなことを起こさない為に予め保存できる値が検証（バリデーション）する必要があります。
 * Laravelのバリデーションは色々な実装方法がありますが、今回はリクエストファイルを作成します。
 * ******************************************/



makeコマンドでファイルの雛形を作ります。

$ php artisan make:request PostRequest



作成したファイルを次のように編集します。
ログインメッセージのカラム名の日本語はlangファイルに記述しましたが、このようにリクエストファイルのattributesに書くこともできます。

app/Http/Requests/PostRequest.php
?



<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:2',
            'body' => 'max:1000',
            'is_public' => 'required|numeric',
            'published_at' => 'required|date_format:Y-m-d H:i',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '内容',
            'is_public' => 'ステータス',
            'published_at' => '公開日',
        ];
    }
}


/********************************************
 * 登録処理（store）
 * 登録処理はstoreメソッドに記述します。
 * 先ほどのPostRequestをインポートして引数にすることでバリデーションエラーがあった場合入力画面に戻る処理を自動的にしてくれます。
 * ******************************************/



app/Http/Controllers/Back/PostController.php
?




namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    // ...

    public function store(PostRequest $request)
    {
        $post = Post::create($request->all());

        if ($post) {
            return redirect()
                ->route('back.posts.edit', $post)
                ->withSuccess('データを登録しました。');
        } else {
            return redirect()
                ->route('back.posts.create')
                ->withError('データの登録に失敗しました。');
        }
    }
}

/********************************************
 * createに成功するとtrueが返ってくるので成功したら編集画面にリダイレクト、それ以外は登録画面にリダイレクトするということをしてます。
 * 例外処理も入れた方が良いかもしれませんが、今回は入れない方向でいきます。
 * ******************************************/




/********************************************
 * フラッシュメッセージの表示
 * リダイレクトしたときに設定しているwithSuccessとwithErrorはフラッシュメッセージを表示するものなのですが、まだ表示するようになっていないので作成しましょう。
 * 次のようにコンポーネントを作成します。
 * ここもBootstrapなので少しややこしくなっていますが、カスタムでテンプレートを作成しているのであれば、フラッシュステータスとスタイルのクラス名を合わせておいた方が楽ですね。

 * ******************************************/

resources/views/components/back/alert.blade.php
?


@php
$flashStatus = [
    'success' => 'success',
    'error' => 'danger',
];
@endphp

@foreach($flashStatus as $key => $value)
    @if (Session::has($key))
        <div class="alert alert-{{ $value }}">
            {{ session($key) }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach

/********************************************
 * レイアウトファイルにコンポーネントを配置します。
 * ******************************************/


resources/views/components/back/layouts/base.blade.php
?


<main class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-back.alert />
                <div class="card">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>

/********************************************
 * 編集画面（edit）
 * 編集画面引数で受け取った値をそのままビューに渡します。
 * ******************************************/



app/Http/Controllers/Back/PostController.php
?


public function edit(Post $post)
{
    return view('back.posts.edit', compact('post'));
}

    /********************************************
     * 新規登録の場合は空なのでForm::openを使用しましたが、
     * 編集の場合は元のデータがあるので、Form::modelを使います。
     *
     * 送信先をupdateに、メソッドをputにしましょう。
     * ******************************************/



resources/views/back/posts/edit.blade.php
?



<?php
$title = '投稿編集';
?>

@extends('back.layouts.base')

@section('content')
<div class="card-header">投稿編集</div>
<div class="card-body">
    {!! Form::model($post, [
        'route' => ['back.posts.update', $post],
        'method' => 'put'
    ]) !!}
    @include('back.posts._form')
    {!! Form::close() !!}
</div>
@endsection

/********************************************
 * 更新処理（update）
 * 更新処理はupdateメソッドを使用します。
 * やってることはstoreとほとんど変わらないですね。
 * ******************************************/


app/Http/Controllers/Back/PostController.php
?


public function update(PostRequest $request, Post $post)
{
    if ($post->update($request->all())) {
        $flash = ['success' => 'データを更新しました。'];
    } else {
        $flash = ['error' => 'データの更新に失敗しました'];
    }

    return redirect()
        ->route('back.posts.edit', $post)
        ->with($flash);
}


/********************************************
 * 削除処理（destroy）
 * 最後は削除です。
 * deleteでレコードの削除をすることができます。
 * ******************************************/


app/Http/Controllers/Back/PostController.php
?



public function destroy(Post $post)
{
    if ($post->delete()) {
        $flash = ['success' => 'データを削除しました。'];
    } else {
        $flash = ['error' => 'データの削除に失敗しました'];
    }

    return redirect()
        ->route('back.posts.index')
        ->with($flash);
}



/********************************************
 * メソッド定義
 * ******************************************/
これで投稿のCRUD機能を作ることができました。
「admin/posts」にアクセスして投稿の一覧を表示して各機能が動作するか確認してみてください。
次回はモデル同士と繋げるリレーションを利用してユーザーと投稿の紐付けを行います。

ソースコードはGitHubに置いてます。
