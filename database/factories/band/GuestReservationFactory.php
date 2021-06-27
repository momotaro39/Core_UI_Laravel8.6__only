<?php

// モデルの位置を変えたので変更したほうが良いらしい
namespace Database\Factories\band;

use App\Models\Band\GuestReservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuestReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuestReservation::class;

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
