<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();
            $table->string('day')->unique()->comment('Day of the week');
            $table->boolean('is_workday')->default(false)->comment('Indicates if it is a workday');
            $table->timestampsWithUser();
        });

        // Seed the table with initial data
        DB::table('workdays')->insert([
            ['day' => 'Monday', 'is_workday' => true],
            ['day' => 'Tuesday', 'is_workday' => true],
            ['day' => 'Wednesday', 'is_workday' => true],
            ['day' => 'Thursday', 'is_workday' => true],
            ['day' => 'Friday', 'is_workday' => true],
            ['day' => 'Saturday', 'is_workday' => false],
            ['day' => 'Sunday', 'is_workday' => false],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workdays');
    }
}