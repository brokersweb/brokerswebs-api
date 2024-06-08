<?php

namespace Database\Factories;

use App\Models\Immovable;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImmovableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Immovable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'      => $this->faker->name,
            'code' => $this->faker->randomNumber($nbDigits = 9, $strict = false),
            'main_image' => $this->faker->imageUrl($width = 640, $height = 480),
            'description' => $this->faker->text($maxNbChars = 200),
            'sale_price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100000),
            'rent_price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100000),
            'enrollment' => $this->faker->randomNumber($nbDigits = 9, $strict = false),
            'video_url' => $this->faker->url,
            'immovabletype_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'owner_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'category' => $this->faker->randomElement($array = array ('sale','rent','both')),
            'co_ownership' => $this->faker->randomElement($array = array ('Si','No')),
            'co_ownership_name' => $this->faker->name, // 'Los Alpes
            'co_ownership_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'status' => $this->faker->randomElement($array = array ('available','unavailable')),
            'building_company_name' => $this->faker->name, // 'Constructora Los Alpes
            'building_company_id' => $this->faker->numberBetween($min = 1, $max = 10),
            'co-adminvalue' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 100000),
            'image_status' => $this->faker->randomElement($array = array ('accepted','rejected','pending')),
            'video_status' => $this->faker->randomElement($array = array ('accepted','rejected','pending')),
        ];
    }
}
