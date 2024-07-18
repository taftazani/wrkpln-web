<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use DateTime;

class FunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('functions')->insert([
            [
                'code' => 'FN001',
                'name' => 'Function One',
                'detail' => 'Details about Function One',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            [
                'code' => 'FN002',
                'name' => 'Function Two',
                'detail' => 'Details about Function Two',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            // Add more functions as needed
        ]);
    }
}