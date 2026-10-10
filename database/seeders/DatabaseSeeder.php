<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Las tablas se crean importando experiencias.sql (no hay migraciones para ellas).
     */
    public function run(): void
    {
        $this->call(MilCumbresSeeder::class);
    }
}
