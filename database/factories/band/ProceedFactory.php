<?php

// モデルの位置を変えたので変更したほうが良いらしい
namespace Database\Factories\band;

use App\Models\Band\Proceed;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProceedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proceed::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
