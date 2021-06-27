<?php

namespace App\Imports;

// モデルの場所を追加
use App\Models\Band\Hall;

// LaravelEXCELで基本的に追加される
use Maatwebsite\Excel\Concerns\ToModel;

class HallsImport implements ToModel
{
    /**
     * インポートする情報を明記していく。
     * modelメソッドの引数が$rowなので配列として入れていく。
     *
     * 配列は上から順番に数字を振って整理する。
     * 暗号化する場合は モデル名::make(変数と配列の番号)
     *
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Hall([
            'name' => $row[0],
            'email' => $row[1],
            'password' => Hash::make($row[2])
        ]);
    }
}
