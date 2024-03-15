<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Creator;

class CreatorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Creator::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'description' => $this->faker->text(),
            'avatar' => $this->faker->text(),
            'website_url' => $this->faker->text(),
            'online' => $this->faker->boolean(),
            'collaboration_id' => $this->faker->randomNumber(),
        ];
    }
}
