<?php

namespace Database\Factories;

use App\Models\ImmovableDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImmovableDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImmovableDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'immovable_id'  => $this->faker->numberBetween($min = 3, $max = 10),
            'bedrooms' => $this->faker->numberBetween($min = 1, $max = 10),
            'bathrooms' => $this->faker->numberBetween($min = 1, $max = 10),
            'hasparkings' => $this->faker->randomElement($array = array ('0','1')),
            'useful_parking_room' => $this->faker->numberBetween($min = 1, $max = 10),
            'total_area' => $this->faker->numberBetween($min = 1, $max = 1000),
            'gross_building_area' => $this->faker->numberBetween($min = 1, $max = 1000),
            'levels' => $this->faker->numberBetween($min = 1, $max = 10),
            'floor_located' => $this->faker->numberBetween($min = 1, $max = 10),
            'stratum' => $this->faker->numberBetween($min = 1, $max = 10),
            'unit_type' => $this->faker->randomElement($array = array ('m2','ft2')),
            'floor_type' => $this->faker->randomElement($array = array ('m2','ft2')),
            'cuisine_type' => $this->faker->randomElement($array = array ('integral','american')),
            'closets' => $this->faker->numberBetween($min = 1, $max = 10),
            'year_construction' => $this->faker->year($max = 'now'),
        ];
    }
}
