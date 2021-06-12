<?php

// モデルの位置を変えたので変更したほうが良いらしい
namespace Database\Factories\band;

use App\Models\band\BandMembersLog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BandMembersLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * 工場の対応するモデルの名前。
     * クラスの中身をモデルに入れる。
     * ※おそらく、カラムを持ってくる
     * @var string
     */
    protected $model = BandMembersLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'band_member_id' => random_int(1, 30),
            'user_id'        => random_int(1, 4),
            'log'            => $this->faker->sentence(40),
        ];
    }
}
