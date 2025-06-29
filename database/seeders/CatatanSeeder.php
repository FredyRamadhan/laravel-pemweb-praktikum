<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Catatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CatatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)
            ->has(Catatan::factory()->count(10))
            ->create();
    }
}
