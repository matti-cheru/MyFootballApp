<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Reservation;
use \App\Models\User;
use \App\Models\Field;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Reservation::class;
    public function definition(): array
    {
        $startTime = $this->faker->time('H:i');
        $endTime = date('H:i', strtotime($startTime . ' +1 hour'));
    
        return [
            'data_prenotazione' => $this->faker->date(),
            'ora_inizio' => $startTime,
            'ora_fine' => $endTime,
            'stato' => 'attesa',
        ];
    }
}
