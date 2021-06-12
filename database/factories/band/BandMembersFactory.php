<?php

// モデルの位置を変えたので変更したほうが良いらしい
namespace Database\Factories\band;

use App\Models\band\BandMembers;
use Illuminate\Database\Eloquent\Factories\Factory;


use Illuminate\Support\Str;


class BandMembersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BandMembers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        return [
            'band_id'      => random_int(1, 3),
            'user_id'      => random_int(1, 3),
            'musical_instrument_id'      => random_int(1, 3),
            // 'name'      => $this->faker->name,
            'email'        => $this->faker->unique()->safeEmail,
            'post'         => $this->faker->postcode,
            'address'      => $this->faker->address,
            'birth'        => $this->faker->dateTimeBetween('-90 years', '-18 years'), // 18歳から90歳までの誕生日を生成
            'phone'        => $this->faker->phoneNumber,
            'claimer_flag' => 0,  // クレーマーフラグ とりあえず全員 0 にしておく
        ];
    }
}
