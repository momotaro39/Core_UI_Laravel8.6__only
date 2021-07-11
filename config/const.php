<?php
return [

    // 外部APiシリーズ
    // envファイルと連携する
    // 使い方 config(const.hogehoge_id);
    // キャッシュクリア php artisan config:cache
    'GOOGLE_API_KEY' => env('GOOGLE_API_KEY'),
    'QIITA_API_KEY' => env('QIITA_API_KEY'),
    'GITHUB_USER_NANE' => env('GITHUB_USER_NANE'),
    'GITHUB_PERSONAL_ACCESS_TOKEN' => env('GITHUB_PERSONAL_ACCESS_TOKEN'),
    'NEWS_API_KEY' => env('NEWS_API_KEY'),
    'DOG_API_KEY' => env('DOG_API_KEY'),
    'LINE_CHANNEL_SECRET' => env('LINE_CHANNEL_SECRET'),
    'LINE_ACCESS_TOKEN' => env('LINE_ACCESS_TOKEN'),







];