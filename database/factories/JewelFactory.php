<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Collection;
use App\Models\Jewel;

class JewelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jewel::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cover' => $this->faker->text(),
            'description' => $this->faker->text(),
            'image' => $this->faker->text(),
            'type' => $this->faker->word(),
            'material' => $this->faker->word(),
            'online' => $this->faker->boolean(),
            'creation_date' => $this->faker->date(),
            'collection_id' => Collection::factory(),
            'collaboration_id' => ::factory(),
        ];
    }
}
