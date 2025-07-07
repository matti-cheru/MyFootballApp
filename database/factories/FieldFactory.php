<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Field;
use \App\Models\Surface;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startHour = random_int(9, 10);     // Il centro sportivo apre alle 9 ed alcuni campi alle 10
        $endHour = random_int(21, 22);      // Il centro sportivo chiude alle 22 ed alcuni campi prima
        $surfaceId = Surface::inRandomOrder()->first()->id;

        return [
            'descrizione' => $this->faker->sentence,
            'id_superficie' => $surfaceId,
            'orario_apertura' => sprintf('%02d:00', $startHour),
            'orario_chiusura' => sprintf('%02d:00', $endHour),
        ];
    }
}
