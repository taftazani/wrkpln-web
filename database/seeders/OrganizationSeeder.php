<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizations')->insert([
            [
                'code' => 'ORG001',
                'name' => 'Organization One',
                'detail' => 'Details about Organization One',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            [
                'code' => 'ORG002',
                'name' => 'Organization Two',
                'detail' => 'Details about Organization Two',
                'status' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
            ],
            // Add more organizations as needed
        ]);
    }
}