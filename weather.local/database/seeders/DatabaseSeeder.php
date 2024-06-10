<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Temperature;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            User::class,
            City::class,
            Temperature::class
        ]);
    }
}
