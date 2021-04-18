<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelsAuthInformation extends Model
{
    use HasFactory;

    //テーブル名を変更したい
    protected $table = 'auth_information';

    //ブラックリスト方式
    protected $guarded = ['id'];
}