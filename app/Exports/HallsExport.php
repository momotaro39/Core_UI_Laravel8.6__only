<?php

namespace App\Exports;

// モデルの場所を追加
use App\Models\Band\Hall;
// LaravelEXCELで基本的に追加する
use Maatwebsite\Excel\Concerns\FromCollection;

// LaravelEXCELでエクスポート時にヘッダーを追加する。
use Maatwebsite\Excel\Concerns\WithHeadings;

// LaravelEXCELでエクスポート時にシートにタイトルを追加する。
use Maatwebsite\Excel\Concerns\WithTitle;


class HallsExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Hall::all();

        // ダウンロードするカラムを指定する場合は次のように記載
        return Hall::select('id', 'name', 'email')->get();
    }

    /**
     * EXCELのヘッダーブブに文字を追加
     * returnの配列がヘッダーのカラム名になる
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * シートのタイトルを設定できます。
     * returnの部分がシート名になります。
     *
     *
     * @return string
     */
    public function title(): string
    {

        return 'test';
    }
}
