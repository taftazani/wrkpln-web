<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginTimeoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_timeouts', function (Blueprint $table) {
            $table->id();
            $table->integer('timeout_hours')->unsigned()->default(1)->comment('Timeout duration in hours (1-24)');
            $table->timestampsWithUser();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_timeouts');
    }
}