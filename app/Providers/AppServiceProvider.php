<?php

namespace App\Providers;

/*
    |--------------------------------------------------------------------------
    | 必要な機能を追加
    |--------------------------------------------------------------------------
    |
    |
    */

use Illuminate\Support\ServiceProvider;

// ページネーション機能を作る時に自動付与
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        /*****************************
         * 次のコマンドで自動で付与されます
         * php artisan vendor:publish --tag=laravel-pagination
         * ***********************/

        Paginator::useBootstrap();
    }
}