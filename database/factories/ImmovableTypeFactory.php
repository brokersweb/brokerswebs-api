<?php

namespace Database\Factories;

use App\Models\ImmovableType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImmovableTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImmovableType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->name,
        ];
    }
}
