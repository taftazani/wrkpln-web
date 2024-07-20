<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('employee_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('System generated code');
            $table->string('type')->comment('Type of employee (permanent, contract, outsource)');
            $table->enum('status',[0, 1])->default(1)->comment('Active Status');

            $table->timestampsWithUser();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('employee_types');
    }
};
