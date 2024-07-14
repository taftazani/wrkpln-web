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
        Schema::create('kpis', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('aspect_id');
            $table->decimal('rate', 5, 2)->default(0);
            $table->decimal('rate_reference', 5, 2)->default(100);
            $table->date('period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
