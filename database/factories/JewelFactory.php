<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Collaboration;
use App\Models\Collection;
use App\Models\Jewel;

use App\Enums\Type;
use App\Enums\Material;
use App\Enums\Status;

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
            'type' => $this->faker->randomElement(Type::cases())->value,
            'material' => $this->faker->randomElement(Material::cases())->value,
            'online' => $this->faker->randomElement(Status::cases())->value,
            'creation_date' => $this->faker->date(),
            'collection_id' => Collection::factory(),
            'collaboration_id' => Collaboration::factory(),
        ];
    }
}
