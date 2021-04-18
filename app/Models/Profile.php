<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelsProfile extends Model
{
    use HasFactory;
    //ブラックリスト方式
    protected $guarded = ['id'];
}