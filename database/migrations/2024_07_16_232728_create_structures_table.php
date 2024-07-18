<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('structures', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('function_id');
            $table->unsignedBigInteger('level_structure_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('cost_center')->nullable();
            $table->integer('plan_man_power')->nullable();
            $table->integer('actual_man_power')->default(0);
            $table->integer('position')->nullable();
            $table->timestampsWithUser();

            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('function_id')->references('id')->on('functions');
            $table->foreign('level_structure_id')->references('id')->on('level_structures');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};