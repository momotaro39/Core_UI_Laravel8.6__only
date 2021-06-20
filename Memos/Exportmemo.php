<?php



/*
|--------------------------------------------------------------------------
| 基本の設定
|--------------------------------------------------------------------------
| 利用するライブラリを追加する
| 公式ドキュメントを確認する
*/

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


/*
|--------------------------------------------------------------------------
| クラスに利用するライブラリを追加する
|--------------------------------------------------------------------------
|
|
*/

class 〇〇Export implements FromQuery, WithMapping, WithHeadings, WithEvents, WithTitle
{
    use Exportable;

// ここに追加していく

}

/*
|--------------------------------------------------------------------------
| 実際に使われていく方法を明記していく
|--------------------------------------------------------------------------
|
|
*/


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


class HourExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting
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
    public function map($productionCosts): array
    {
        // データベースの情報を配列形式に変更
        $productionCosts = $productionCosts->toArray();

        //データベースに入っている情報は変数にセット。
        // データベースにセットしていないデータはコンストファイルから取得
        //nullが入るデータは配列に入れるだけではエラーが起こるため、条件演算子を活用して変数にセット
        $section = isset($productionCosts['user']) ? $productionCosts['user']['m_section']['name'] : '';
        $team = isset($productionCosts['user']) && isset($productionCosts['user']['m_team']) ? $productionCosts['user']['m_team']['name'] : '';
        $user = isset($productionCosts['user']) ? $productionCosts['user']['name'] : '';
        $project = isset($productionCosts['project']) ? $productionCosts['project']['name'] : '';
        $task = isset($productionCosts['task']) ? $productionCosts['task']['name'] : '';

        return [
            $productionCosts['regist_date'],
            $section,
            $team,
            $user,
            $project,
            $task,
            number_format($productionCosts['performance_production_cost'], 2),
            $productionCosts['memo'],
        ];
    }

    /**
     * WithHeadingsで使用するヘッダーをセット
     * @return array
     */
    public function headings(): array
    {
        return HourController::$headers;
    }

    /**
     * EXCELの小数点第2位まで出力
     * 形式は以下を参照
     * namespace PhpOffice\PhpSpreadsheet\Style;のNumberFormatに形式が登録されています。
     * columnFormats()のリターンに入力します。
     * 列名 => フォーマット形式
     *  @return array
     */
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}


class NotificationExport implements FromView, WithEvents, WithTitle
{
    use Exportable;
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return $this->view;
    }

    /**
     * スタンプパスをセット
     *
     * @param $userId
     */
    public function setStampPath($userId)
    {
        $user =  User::find($userId);
        //ユーザーが存在する かつ ユーザーの姓が登録されている場合
        if (isset($user) && isset($user->last_name) && $user->last_name != '') {
            $this->path = User::getStampPath($userId);
        }
        return $this;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // デフォルトのフォントと文字サイズを指定
                $sheet = $event->getSheet()->getDelegate();
                $sheet->getDefaultRowDimension()->setRowHeight(26.25);
                $sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' =>  'ＭＳ Ｐゴシック',
                        'size' => 11,
                    ],
                ]);
                // 印刷範囲指定
                $sheet->getPageSetup()->setPrintArea('A1:Q29');
                /* 幅と高さを1ページに収める */
                $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(1);
                //$sheet->getPageSetup()->setVerticalCentered(true); //上下の中央揃え
                $sheet->getPageSetup()->setHorizontalCentered(true); //左右の中央揃え
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4); //A4サイズ
                // マージン指定
                $sheet->getPageMargins()->setTop(0.50); //上
                $sheet->getPageMargins()->setBottom(0.25); //下
                $sheet->getPageMargins()->setLeft(0.50); //左
                $sheet->getPageMargins()->setRight(0.50); //右
                $sheet->getPageMargins()->setHeader(0.25); //ヘッダ
                $sheet->getPageMargins()->setFooter(0.25); //フッタ
                //スタンプ設定
                if (isset($this->path)) {
                    $drawing = new Drawing();
                    $file = $this->path;
                    $drawing->setName('Stamp');
                    $drawing->setDescription('Stamp');
                    $drawing->setPath($file);
                    $drawing->setHeight(26);
                    $drawing->setOffsetY(4);
                    $drawing->setOffsetX(4);
                    $drawing->setCoordinates('P7');
                    $drawing->setWorksheet($sheet);
                }
            },
        ];
    }

    public function title(): string
    {

        return '届出書';
    }
}


class PartnerExport implements FromQuery, WithMapping, WithHeadings
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
     * クエリビルダを使用するときにせっと
     * @return type
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * WithMappingで使用するデータをセット
     * 見出し行をセットしています。
     * @param type $account
     * @return array
     */
    public function map($partner): array
    {
        $partner = $partner->toArray();
        return [
            sprintf('%04d', $partner['id']),
            $partner['name'],
            $partner['name_kana'],
            $partner['business_summary'],
            $partner['zip_code'],
            Config('const.pref')[$partner['pref_code']],
            $partner['address'],
            $partner['tel'],
            $partner['fax'],
            $partner['personal_name1'],
            $partner['personal_name2'],
            $partner['personal_name3'],
        ];
    }

    /**
     * WithHeadingsで使用するヘッダーをセット
     * 見出し行で使う時は配列を利用する。
     * @return array
     */
    public function headings(): array
    {
        return PartnerController::$headers;
    }
}


class ProjectExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting
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
    public function map($project): array
    {
        // データベースの情報を配列形式に変更
        $project = $project->toArray();

        //データベースに入っている情報は変数にセット。
        // データベースにセットしていないデータはコンストファイルから取得
        //nullが入るデータは配列に入れるだけではエラーが起こるため、条件演算子を活用して変数にセット
        $section = isset($project['section']) ? $project['section']['name'] : '';
        $team = isset($project['m_team']) ? $project['m_team']['name'] : '';
        $customer = isset($project['m_customer']) ? $project['m_customer']['name'] : '';
        $displayManagementNumber = isset($project['numbering_ledger']['id']) ? $project['numbering_ledger']['serial_number'] . '-' . sprintf('%02d', $project['numbering_ledger']['month']) . '-'  . sprintf('%04d', $project['m_customer']['code']) . '-'  . sprintf('%04d', $project['numbering_ledger']['management_number']) : '';

        // 合計を取得する 見積もり工数
        //空の配列作成
        $estimated_production_costs_returnArray = [];

        // タスクテーブルの連想配列の「estimated_production_costs」を取得する
        foreach ($project['tasks'] as $aKey => $aValue) {
            foreach ($aValue as $bKey => $bValue) {
                // 「estimated_production_costs」カラムの値を選び、配列に入れる
                if ($bKey == 'estimated_production_costs')
                    $estimated_production_costs_returnArray[] = $bValue;
            }
        }

        //配列の合計をarray_sumメソッドで計算
        $totalEstimatedProductionCost =  array_sum($estimated_production_costs_returnArray);
        //配列の合計に数字があれば、数字出力。なければ空白
        $totalEstimatedProductionCost =  isset($totalEstimatedProductionCost) ? $totalEstimatedProductionCost : '';


        // 合計を取得する CCPM工数
        //空の配列作成
        $ccpmProductionCostsReturnArray = [];

        // タスクテーブルの連想配列の「ccpm_production_costs」を取得する
        foreach ($project['tasks'] as $aKey => $aValue) {
            foreach ($aValue as $bKey => $bValue) {
                // 「ccpm_production_costs」カラムの値を選び、配列に入れる
                if ($bKey == 'ccpm_production_costs')
                    $ccpmProductionCostsReturnArray[] = $bValue;
            }
        }
        //配列の合計をarray_sumメソッドで計算
        $totalCcpmProductionCostsReturn =  array_sum($ccpmProductionCostsReturnArray);
        //配列の合計に数字があれば、数字出力。なければ空白
        $totalCcpmProductionCostsReturn =  isset($totalCcpmProductionCostsReturn) ? $totalCcpmProductionCostsReturn : '';


        // 合計を取得する 実績工数
        //空の配列作成
        $performanceProductionCostsArray = [];
        // 工数入力テーブルの連想配列の「performance_production_cost」カラムを取得する
        foreach ($project['production_costs'] as $aKey => $aValue) {
            foreach ($aValue as $bKey => $bValue) {
                // 「performance_production_cost」カラムの値を選び、配列に入れる
                if ($bKey == 'performance_production_cost')
                    $performanceProductionCostsArray[] = $bValue;
            }
        }
        //配列の合計をarray_sumメソッドで計算
        $totalPerformanceProductionCostsReturn =  array_sum($performanceProductionCostsArray);
        //配列の合計に数字があれば、数字出力。なければ空白
        $totalPerformanceProductionCostsReturn =  isset($totalPerformanceProductionCostsReturn) ? $totalPerformanceProductionCostsReturn : '';

        return [
            $project['code'],
            Config('const.project_type_name')[$project['project_type']],
            $displayManagementNumber, //見積書
            isset($project['numbering_ledger']) ? $project['numbering_ledger']['project_title'] : null,
            $section,
            $team,
            $customer,
            $project['name'],
            Config('const.project_status_name')[$project['status']],
            //カンマ区切り表記
            number_format($project['order_amount']),
            number_format($project['purchase_amount']),
            //小数点第2位表記
            number_format($totalEstimatedProductionCost, 2),               //合計見積工数(h)
            number_format($totalCcpmProductionCostsReturn, 2),                //合計CCPM工数（ｈ）
            number_format($totalPerformanceProductionCostsReturn, 2),           //  合計実績工数(h)
            $project['github_repository_name'],
        ];
    }

    /**
     * WithHeadingsで使用するヘッダーをセット
     * @return array
     */
    public function headings(): array
    {
        return ProjectController::$headers;
    }

    /**
     * EXCELの小数点第2位まで出力
     * 形式は以下を参照
     * namespace PhpOffice\PhpSpreadsheet\Style;のNumberFormatに形式が登録されています。
     * columnFormats()のリターンに入力します。
     * 列名 => フォーマット形式
     *  @return array
     */
    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,  //受注金額
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,  //仕入金額
            'L' => NumberFormat::FORMAT_NUMBER_00,  //合計 見積工数
            'M' => NumberFormat::FORMAT_NUMBER_00,  //合計 CCPM工数
            'N' => NumberFormat::FORMAT_NUMBER_00,  //合計 実績工数
        ];
    }
}

class ProjectOrderExport implements FromView, WithEvents, WithTitle
{
    use Exportable;
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return $this->view;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // デフォルトのフォントと文字サイズを指定
                $sheet = $event->getSheet()->getDelegate();
                $sheet->getDefaultRowDimension()->setRowHeight(25);
                $sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name' =>  'ＭＳ Ｐゴシック',
                        'size' => 11,
                    ],
                ]);

                $align = new Align();
                // 印刷範囲指定
                $sheet->getPageSetup()->setPrintArea('A1:M54');
                /* 幅と高さを1ページに収める */
                $sheet->getPageSetup()->setFitToWidth(1)->setFitToHeight(1);
                $sheet->getPageSetup()->setVerticalCentered(true); //上下の中央揃え
                $sheet->getPageSetup()->setHorizontalCentered(true); //左右の中央揃え
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4); //A4サイズ
                // マージン指定
                $sheet->getPageMargins()->setTop(0.25); //上
                $sheet->getPageMargins()->setBottom(0.25); //下
                $sheet->getPageMargins()->setLeft(0.25); //左
                $sheet->getPageMargins()->setRight(0.25); //右
                $sheet->getPageMargins()->setHeader(0.25); //ヘッダ
                $sheet->getPageMargins()->setFooter(0.25); //フッタ
                /* 斜め線を引く */
                $sheet->getStyle("E36")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("G36")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I36")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("E37")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("G37")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I37")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("E40")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("G40")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I40")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("E41")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("G41")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I41")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("I46")->getBorders()->setDiagonalDirection(2)->getDiagonal()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }

    public function title(): string
    {

        return 'プロジェクト指図書';
    }
}

    /****************************************** */
    /*
    /****************************************** */



