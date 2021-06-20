<?php

namespace App;

namespace App;

use App\Providers\EventServiceProvider;

// 【1対多】のリレーションを定義するメソッドがあります。

// hasManyメソッド　【1対多】のリレーションを定義する
// →【1】側で定義する（Authorモデル）
// belongsToメソッド　【1対多】の逆向きのリレーションを定義する
// →【多】側で定義する（Bookモデル）


// Public function 接続先の名称を単数か複数で設定()
// {
//   //「user_id」以外の外部キーにしたい
//   // さらにusers.id 以外のidカラムにしたい
//   return $this->hasOne('App\User', '子のモデルの外部キー', '親のモデルの id キー');
// }
  // 「１対多」の「多」側 → メソッド名は複数形
//   public function posts()
//   {
//     return $this->hasMany('App\Post');
//   }

  // 「１対１」→ メソッド名は単数形
//   Public function user()
//   {
//     return $this->belongsTo('App\User');
//   }
// }


public function company()
{
    return $this->hasOne(Company::class, '相手（子）', '自分のid');
    return $this->hasOne(Company::class, 'user_id', 'id');
}




 /*
 |--------------------------------------------------------------------------
 | HasOneの引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | １対１の関係(ユーザーと個人情報など）
 | ユーザーに紐付いた個人情報のリレーションを定義
 |
 |
 */


// リレーション先の単数形
Public function profile()
{
  // リレーションするモデルの名称::class
    return  $this->hasOne(Profile::class);
}

    /*
    |--------------------------------------------------------------------------
    | HasOneの引数の使い方 2つまでVer 参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
    |--------------------------------------------------------------------------
    | 使い所
    | User_id以外のカラムと紐づけたい場合
    | デフォルトだとUser_idを見てしまうので、この規約を上書き（オーバーライド）します。
    | ※第2引数をもたせます
    | ※foreign_key 外部キー
    */



Public function profile()
{
    return $this->hasOne(Profile::class, 'foreign_key');
}


    /*
    |--------------------------------------------------------------------------
    | belongsTo の引数の使い方 2つまでVer 参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
    |--------------------------------------------------------------------------
    |
    |  Eloqunetでは「リレーションメソッド名 ＋ 〇〇_idのサフィックス」をつけたものをデフォルトの外部キーにしているため、
    |  外部キーが異なる場合はメソッドの第２引数にカスタムキー名を渡す必要がある。
    |
    */



Public function user()
{
    return $this->belongsTo(User::class, 'foreign_key');
}

/*
    |--------------------------------------------------------------------------
    | belongsTo の引数の使い方 3つまでVer 参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
    |--------------------------------------------------------------------------
    |
    | 第3引数まででほぼ完成するが使い方を明記しておく。
    | 親キーの主キーがIDではない、もしくは、子のモデルとは異なるカラムで紐づけたい場合は、第３引数を渡す。
    |     |
    */



Public function user()
{
    return $this->belongsTo(User::class, 'foreign_key', 'other_id');
}



/*
 |--------------------------------------------------------------------------
 | hasManyの引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | １対多の場合
 | メソッド名が複数形になるのがポイント
 | Mは大文字なので注意
 |
 */

// メソッド名が複数形になる
Public function posts()
{
    // モデル名::class
    return $this->hasMany(Post::class)
}


 /*
 |--------------------------------------------------------------------------
 | hasManyの第2引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | １対多の場合  (１→多)
 | 外部キーとローカルキーを与える引数に渡すこと上書き（オーバーライド）できる
 | $this->hasMany(モデル名::class, '相手先の外部キー 〇〇_id', '自分の主キー');
 |
 */
// メソッド名が複数形になる
Public function posts()
{
    // モデル名::class
    return $this->hasMany(Post::class, 'post_id', 'id');
}

 /*
 |--------------------------------------------------------------------------
 | Inverseの引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | 多対1の場合  （多→１）
 | 1対1の時と同様。
 |
 |
 */

 // メソッド名が単数形になる
Public function post()
{
    Return $this->belongsTo(Post::class);
}

 /*
 |--------------------------------------------------------------------------
 | belognsToManyの引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | 多対多の場合  （多→多）
 | ユーザーと役目の関係
 | ユーザーが多くの役目をもっている
 | 役目も多くのユーザーに共有されている状況
 |
 |
 |
 |
 */

//  メソッドは複数形
Public function roles()
{
    return $this->belognsToMany(Role::class);
}

  /*
 |--------------------------------------------------------------------------
 | belognsToManyの第2.3引数の使い方  参照先 https://qiita.com/mtakehara21/items/3cef9d12869d162e1ce9
 |--------------------------------------------------------------------------
 | 使い所
 | 多対多の場合  （多→多）
 |
 | 第2引数： 結合テーブル名
 | 第3引数： リレーションを定義しているモデルの外部キー名
 | 第4引数： 結合するモデルの外部キー名
 */

//  メソッドは複数形
Public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
}



// User_table

    /**
     * The "type" of the auto-incrementing ID.
     * 主キーの自動採番と型の設定
     * @var string
     */
    protected $keyType = 'integer';


    /*
    |--------------------------------------------------------------------------
    | 主従関係を明確にリレーションを張る
    |--------------------------------------------------------------------------
    |
    | 主  〇〇管理者 1 or 多 (hasMany hasOne)
    | 従  〇〇〇〇 1 or 多(belongsTo belongsToMany)
    | *(相手先モデル名（アッパーキャメル）::クラス名,相手テーブルの外部キー,ローカルキー)
    |
    |※命名規則
    |  function名は、ローワーキャメルケースで記入。
    |※※hasManyの場合は複数形、hasOneの場合は単数形が理想
    |
    |
    */


 /*
 |--------------------------------------------------------------------------
 | 主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 | 1 バンド
 | 主（・） ⇔ 従（・）
 | 2
 |
 | 3
 |
 |
 */


Public function bands()
{
  return $this->belongsTo(Band::class);
}

Public function bands()
{
  return $this->belongsTo(Admin_role::class);
}

public function guest_resevations()
{
return $this->hasOne(Guest_resevation::class, 'user_id', 'id');
}



// Admin_roles
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */

public function user()
{
return $this->hasOne(User::class, 'admin_role_id', 'id');
}

// Labeles

public function band()
{
return $this->hasMany(Band::class, ',label_id', 'id');
}


// Event
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
  // 「１対１」→ メソッド名は単数形
  Public function hall()
  {
    return $this->belongsTo(Hall::class);
  }

  public function guest_resevations()
  {
  return $this->hasOne(Guest_resevation::class, 'event_id', 'id');
  }

  public function Proceed()
  {
  return $this->hasOne(Proceed::class, 'event_id', 'id');
  }

  public function entry()
  {
  return $this->hasOne(Entry::class, 'event_id', 'id');
  }

  public function performance_list()
  {
  return $this->hasOne(PerformanceList::class, 'event_id', 'id');
  }

// ticket
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
public function Proceeds()
{
return $this->hasMany(Proceed::class, ',ticket_rank_id', 'id');
}

// guest_reserve
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */

  Public function user()
  {
    return $this->belongsTo(User::class);
  }


  Public function event()
  {
    return $this->belongsTo(Event::class);
  }



// Proceed
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function ticket_list()
{
  return $this->belongsTo(TicketList::class);
}


// performance_list
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function band()
{
  return $this->belongsTo(Band::class);
}

Public function music()
{
  return $this->belongsTo(Music::class);
}

// entrys
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
Public function event()
{
  return $this->belongsTo(Event::class);
}

Public function band()
{
  return $this->belongsTo(Band::class);
}

// halls
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
public function events()
{
return $this->hasMany(Events::class, 'hall_id', 'id');
}

// music
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
Public function album()
{
  return $this->belongsTo(Album::class);
}

public function band()
{
return $this->belongsTo(Band::class);
}

public function performance_list()
{
return $this->hasOne(PerformanceList::class, 'music_id', 'id');
}



// album
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
Public function band()
{
  return $this->belongsTo(Band::class);
}

public function musics()
{
return $this->hasMany(Music::class, 'album_id', 'id');
}

// bandgoods
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
  // 「１対１」→ メソッド名は単数形
  Public function band()
  {
    return $this->belongsTo(Band::class);
  }


    Public function goods_type()
    {
      return $this->belongsTo(GoodsType::class);
    }

// goodstype
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
public function band_goods()
{
return $this->hasMany(Band_goods::class, 'goods_type_id', 'id');
}

// musical
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
public function band_member()
{
return $this->hasMany(Band_member::class, 'mudical_instrument_id', 'id');
}


// bandmember
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */

  // 「１対１」→ メソッド名は単数形
  Public function band()
  {
    return $this->belongsTo(Band::class);
  }

  // 「１対１」→ メソッド名は単数形
  Public function musical_instrument()
  {
    return $this->belongsTo(BandMember::class);
  }


// bandmemberlog



// band
 /*
 |--------------------------------------------------------------------------
 | リレーション主従の関係
 |--------------------------------------------------------------------------
 | 接続するモデルの詳細
 | 主（外部キー・主キー） ⇔ 従（外部キー・主キー）
 |
 | 1 ユーザー
 | 主（・） ⇔ 従（・）
 |
 | 2 バンド
 | 主（・） ⇔ 従（・）
 |
 | 3
 | 主（・） ⇔ 従（・）
 |
 |
 */
public function users()
{
return $this->hasMany(User::class, 'band_id', 'id');
}

Public function lebels()
{
  return $this->belongsTo(Label::class);
}

public function albums()
{
return $this->hasMany(Album::class, 'band_id', 'id');
}

public function musics()
{
return $this->hasMany(Music::class, 'band_id', 'id');
}


public function band_members()
{
return $this->hasMany(BandMember::class, 'band_id', 'id');
}

public function band_goods()
{
return $this->hasMany(BandGoods::class, 'band_id', 'id');
}

public function performance_list()
{
return $this->hasOne(PerformanceList::class, 'band_id', 'id');
}

public function entry()
{
return $this->hasOne(Entry::class, 'band_id', 'id');
}
