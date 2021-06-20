<?php


/*
|--------------------------------------------------------------------------
| Laravel: 色んなModelで共通のメソッドをTraitで定義する  参照先  https://note.com/watarunakayama/n/n4fb2794c3514
|--------------------------------------------------------------------------
|
|
|
|
*/



/********************************************
 * メソッド定義
 * ******************************************/

この記事が役立ちそうなひと
・色んな Model に似たような処理をたくさん書いてる人
・View にif文を書きすぎて大変な思いをしている人
・いつ Trait を使うべきかよくわからない人
今回やりたいこと
・色んなModel に created_at と updated_at のカラムがある
・updated_at があるなら updated_at を更新日として表示したい
・updated_at がないときは代わりに created_at を更新日として表示する
・上記の処理を、色んな Model 共通で実装したい
やらなきゃいけないこと
・Trait に共通の処理を書く
・その Trait を Model で使う
・あとは好きなところで Trait の処理を呼ぶ
実際にやってみる
今回は以下のような Model を前提としてみます。

・User（ユーザー）
・Post（投稿）
・Comment（投稿に対するユーザーコメント）
まずプロジェクトの app ディレクトリ直下に Traits フォルダを作ります。

cd path/to/laravel-project/app
mkdir Traits
次に app/Traits の中に Timestamp.php というファイルを作成します。やりたいことの通り、updated_at の有無によって、updated_at か created_at のどちらかを返すだけです。

<?php namespace App\Traits;

trait Timestamp
{
    public function getModifiedAtAttribute()
    {
        return ($this->updated_at) ? $this->updated_at : $this->created_at;
    }
}
これを User.php の Model内で使えるように宣言しましょう。

<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\Timestamp; //追加

class User extends Authenticatable
{
    use Notifiable;
    use Timestamp; //追加

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
あとはこんな風に呼び出すだけで、Timestamp.php で定義された getModifiedAtAttribute() の値を取得することができます。

$user = App\Modesl\User::find(1);
echo $user->modified_at; // Timestamp.php の getModifiedAtAttribute() が実行される
共通処理なので、 Post.php と Comment.php の Model でも Trait を宣言してみましょう。

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Timestamp; //追加

class Post extends Model
{
    use Timestamp; //追加

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Timestamp; //追加

class Comment extends Model
{
    use Timestamp; //追加

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
これで全ての Model で、Trait の関数が使えるようになりました。

$user = App\Modesl\User::find(1);
echo $user->modified_at;

$post = App\Modesl\Post::find(1);
echo $post->modified_at;

$comment = App\Modesl\Comment::find(1);
echo $comment->modified_at;
何をしてるのかわからんぞ！
「いきなり getModifiedAtAttributeってなんじゃい！」という方へ説明しますと、これは Laravel の機能の一つである「アクセサ（Accessor）」を使っています。詳しくはリファレンスをお読みください。

リファレンスの命名規則にしたがって関数を定義すれば、いい感じに Attributes へ要素を動的に追加できるステキ機能です。これでViewもスッキリしますね！

サンプルソース
上記で使ったソースはGitHubに公開しています。（モデル定義の練習も含めて、こちらのリポジトリをForkさせて頂いています）





cloneしたら .env を書き換えて、下記のコマンドでサンプルデータをもりっと生成して動作確認できます。よかったらどうぞ。

cd path/to/project
composer install
php artisan key:generate
php artisan migrate:refresh --seed


