<?php






/*
|--------------------------------------------------------------------------
| 動的なセレクトボックスを作る  参照先  https://teratail.com/questions/274956
|--------------------------------------------------------------------------
| 前提・実現したいこと
|
| Laravelで一つのセレクトボックスの選択によって、二つ目のセレクトボックスの内容が変わるようにしたいです。
|
| 動物の種類（犬）を選択すると、二つ目のセレクトボックスに犬の詳細な種類の選択が出るというものです。
|
*/

    /*****************************************
     *  View
    /****************************************** */

<form action="{{ route('post.store') }}">
     <div class="form-group col-sm-6">
       <label for="category">ペットの種類</label>
       <select class="form-control" name="category_id" id="main"> //ここの
         <option value="" style="display: none;">選択してください</option>
         @foreach ($categoryList as $index => $name)
           <option value="{{ $index }}">{{ $name }}</option>
         @endforeach
       </select>
       <label for="category">ペットの詳細な種類</label>
       <select class="form-control" name="sub_category_id" id="sub">
         @foreach ($subCategoryList as $index => $name)
           <option value="{{ $index }}" data-val="">{{ $name }}</option>
         @endforeach
       </select>
     </div>
</form>

    /*****************************************
     *  Jquery
    /****************************************** */


// セレクトボックスの連動
  // 親カテゴリのselect要素が変更になるとイベントが発生
  $('#parent').change(function () {
    var cate_val = $(this).val();

    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/fetch/category', //controllerで定義
      type: 'POST',
      data: {'category_val' : cate_val},  //変数の名称を入れる
      datatype: 'json',
    })


    // 成功した時の対応
    // データは変数に代入されているデータを使用する
    .done(function(data) {

        // #childrenで子カテゴリーを示す
      // 子カテゴリのoptionを一旦削除（remove）
      $('#children option').remove();

      // DBから受け取ったデータを子カテゴリのoptionにセット
    //   子カテゴリ（子カテゴリchiledrenのoption）に追加する
      $.each(data, function(key, value) {
        $('#children').append($('<option>').text(value.name).attr('value', key));
      })
    })
    .fail(function() {
      console.log('失敗');
    });

  });

    /*****************************************
     *  コントローラー
    /****************************************** */
Route::post('/fetch/category', 'PostController@fetch')->name('post.fetch');


    /*****************************************
     *   メソッド定義
    /****************************************** */

     /**
     * ajaxリクエストを受け取り、サブカテゴリを返す
     */
    public function fetch(Request $request) {
        $cateVal = $request['category_val'];
        $subCategory = SubCategory::where('category_id', $cateVal)->get();
        return $subCategory;
    }

