<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Surface;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\DataLayer;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createSurfaces();
        $this->createUsers();
        $this->populateDB();
    }

    private function populateDB()
    {
        // Creazione di 8 campi
        for ($i = 1; $i <= 17; $i++)
        {
            Field::factory()->create([
                'nome_campo' => 'Campo ' . $i,
            ]);
        }


        // Creazione di prenotazioni per utenti player
        $registeredPlayers = User::where('role','registered_player')->get();
        $allFields = Field::all();

        if ($allFields->isEmpty())
        {
            return;
        }

        foreach ($registeredPlayers as $user)
        {
            $numberOfReservations = random_int(5, 8);

            for ($i = 0; $i < $numberOfReservations; $i++)
            {
                $field = $allFields->random();

                $startHour = random_int(9, 19);
                $startTime = sprintf('%02d:00:00', $startHour);

                $endHour = ($startHour + 1) % 24;
                $endTime = sprintf('%02d:00:00', $endHour);

                Reservation::create([
                    'user_id' => $user->id,
                    'id_campo' => $field->id,
                    'data_prenotazione' => Carbon::now()->addDays($i * 2)->format('Y-m-d'), // Data futura
                    'ora_inizio' => $startTime,
                    'ora_fine' => $endTime,
                ]);
            }
        }
        $users = User::where('role', 'registered_player')->get();
        $fields = Field::take(4)->get();
    }

    private function createUsers()
    {
        User::factory()->create([
            'name' => 'Mattia Cherubini',
            'email' => 'mattia.cherubini@gmail.com',
            'password' => 'Cheruilbello0!',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Roberto Cherubini',
            'email' => 'roberto.cherubini@gmail.com',
            'password' => 'RobyCheru1!'
        ]);

        User::factory()->create([
            'name' => 'Gabriele Ceresara',
            'email' => 'gabriele.ceresara@gmail.com',
            'password' => 'GabriCecio1!'
        ]);

        User::factory()->create([
            'name' => 'Chiara Cherubini',
            'email' => 'chiara.cherubini@gmail.com',
            'password' => 'ChiaraCheru1!'
        ]);

    }

    private function createSurfaces()
    {
        Surface::factory()->count(1)->create(['nome' => "Erba Sintetica"]);
        Surface::factory()->count(1)->create(['nome' => "Erba Naturale"]);
        Surface::factory()->count(1)->create(['nome' => "Terra Battuta"]);
    }
}