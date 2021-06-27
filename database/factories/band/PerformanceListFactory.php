<?php

// モデルの位置を変えたので変更したほうが良いらしい
namespace Database\Factories\band;

use App\Models\Band\PerformanceList;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerformanceListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PerformanceList::class;

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
