<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            RolesSeeder::class,
            EstadosGeneralesSeeder::class,
            OficinaSeeder::class,
            InspectorSeeder::class,
            CalleSeeder::class,
            JuecesSeeder::class,
            JuzgadosSeeder::class,
            OficinasInternasSeeder::class,
            InfraccionSeeder::class,
        ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
