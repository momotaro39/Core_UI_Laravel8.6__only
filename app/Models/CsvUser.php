<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvUser extends Model
{
    use HasFactory;

    //ブラックリスト方式
    protected $guarded = ['id'];
}