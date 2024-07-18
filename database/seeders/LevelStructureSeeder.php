<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use DateTime;

class LevelStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level_structures')->insert([
            [
                'code' => 'LVL001',
                'name' => 'Level One',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            [
                'code' => 'LVL002',
                'name' => 'Level Two',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            // Add more levels as needed
        ]);
    }
}