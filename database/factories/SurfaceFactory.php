<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Surface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surface>
 */
class SurfaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->word(2, true),
        ];
    }
}
