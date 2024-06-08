<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'nit' => $this->faker->numberBetween($min = 1000000000, $max = 9999999999),
            'phone' => $this->faker->phoneNumber,
            'cellphone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'url_website' => $this->faker->url,
            'address_id' => $this->faker->numberBetween($min = 1, $max = 10),
        ];
    }
}
