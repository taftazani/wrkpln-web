<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
                $post = new User();
                $post->name = 'Super Admin';
                $post->user_id = 'admin';
                $post->email = 'admin@mail.com';
                $post->role = 1;
                $post->password = Hash::make('admin123');
                $post->tgl_lahir = date('Y-m-d');
                $post->tgl_masuk = date('Y-m-d');
                $post->phone = '082121717079';
                $post->save();
        }
}