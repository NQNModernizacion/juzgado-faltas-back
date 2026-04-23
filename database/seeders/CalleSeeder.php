<?php

namespace Database\Seeders;

use App\Models\Calle;
use Illuminate\Database\Seeder;

class CalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/calles.json'));
        $calles = json_decode($json, true);

        foreach ($calles as $calle) {
            Calle::updateOrCreate(
                ['codigo' => $calle['RV_LOW_VALUE']],
                ['nombre' => $calle['RV_MEANING']]
            );
        }
    }
}
