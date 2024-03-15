<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Collaboration;
use App\Models\Collection;

class CollaborationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Collaboration::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'image' => $this->faker->text(),
            'online' => $this->faker->boolean(),
            'creation_date' => $this->faker->date(),
            'collection_id' => Collection::factory(),
        ];
    }
}
