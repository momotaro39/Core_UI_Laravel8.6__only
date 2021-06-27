<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use App\Http\Controllers\Master\CustomerController;

class CustomerExport implements FromQuery, WithMapping, WithHeadings, WithEvents, WithTitle
{
    use Exportable;

    /**
     * Controllerから受け取ったQueryをセット
     * @param type $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * FromQueryで使用するqueryをセット
     * @return type
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * WithMappingで使用するデータをセット
     * @param type $account
     * @return array
     */
    public function map($customer): array
    {
        $customer = $customer->toArray();
        return [
            sprintf('%04d', $customer['code']),
            $customer['name'],
            $customer['name_kana'],
            $customer['business_summary'],
            $customer['zip_code'],
            Config('const.pref')[$customer['pref_code']],
            $customer['address'],
            $customer['tel'],
            $customer['fax'],
            $customer['personal_name1'],
            $customer['personal_name2'],
            $customer['personal_name3'],
        ];
    }

    /**
     * WithHeadingsで使用するヘッダーをセット
     * @return array
     */
    public function headings(): array
    {
        return CustomerController::$headers;
    }

    /**
     * シートの名称を変更
     *
     * @return string
     */
    public function title(): string
    {

        return '顧客マスタ情報';
    }

    /**
     * シートのデザイン編集設定
     *
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->getSheet()->getDelegate();
                $sheet->setShowGridlines(true);  //trueで目盛線を表示する
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                // ORIENTATION_DEFAULT = 'default';  //デフォルト
                // ORIENTATION_LANDSCAPE = 'landscape';  //横
                // ORIENTATION_PORTRAIT = 'portrait';  //縦
            },
            // フォントの変更
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->getSheet()->getDelegate();
                $sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' => 'ＭＳＰゴシック',  //フォント
                        'size' => 12,  //文字サイズ
                        // 'bold' => true,  //ボールド体
                        // 'italic' => true,  //イタリック体
                        // 'superscript' => true,  //上付き文字
                        // 'subscript' => false,  //下付き文字
                        // 'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_DOUBLE,  //下線
                        // 'strikethrough' => true,  //取り消し線
                        'color' => [
                            // 'rgb' => 'FF0000'  //色
                        ],
                        //配置の設定
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,  //水平
                            'vertical' => Alignment::VERTICAL_CENTER,  //垂直
                            'textRotation' => 0,  //回転
                            'wrapText' => FALSE,  //折返し
                        ],
                    ],
                ]);
                // カラムの幅を変更します
                $width = [
                    'A' => 11,
                    'B' => 38,
                    'C' => 18,
                    'D' => 36,
                    'E' => 9,
                    'F' => 9,
                    'G' => 45,
                    'H' => 11,
                    'I' => 11,
                    'J' => 9,
                    'K' => 9,
                    'L' => 9,
                ];
                // それぞれの大きさに変更する
                foreach ($width as $column => $value) {
                    $sheet->getColumnDimension($column)
                        ->setAutoSize(false)
                        ->setWidth($value);
                }
                // Set autosized to true
                $sheet->hasFixedSizeColumns = true;
                // セルの高さを変更する
                $sheet->getDefaultRowDimension()->setRowHeight(20);
            },

        ];
    }
}
