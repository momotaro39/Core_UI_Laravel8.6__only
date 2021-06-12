<?php

namespace App\Exports;

use App\User;
use App\Models\User; //Laravel8の場合
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class UsersExport implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }

    // public function collection()
    // {
    //     return User::where('id', 1)->get();
    // }

    /**
     * カラムの名称をつける
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
     * シートに名前をつける
     */
    public function title(): string
    {

        return 'test';
    }


    /**
     * ダウンロー蘇づる列を指定してもOK
     */
    public function collection3()
    {
        return User::select('id', 'name', 'email')->get();
    }
}