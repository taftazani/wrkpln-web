<?php

namespace Database\Seeders;

use App\Models\KpiAspect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KpiAspectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $post = new KpiAspect();
        $post->name = 'Services';
        $post->save();

        $post = new KpiAspect();
        $post->name = 'Upselling';
        $post->save();

        $post = new KpiAspect();
        $post->name = 'Attitude';
        $post->save();

        $post = new KpiAspect();
        $post->name = 'Knowledge';
        $post->save();

        $post = new KpiAspect();
        $post->name = 'Appearance';
        $post->save();

        $post = new KpiAspect();
        $post->name = 'Absensi';
        $post->save();
    }
}
