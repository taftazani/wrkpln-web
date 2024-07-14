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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_user')->nullable();
            $table->unsignedInteger('id_absen')->nullable();
            $table->unsignedInteger('id_izin')->nullable();
            $table->unsignedInteger('id_lembur')->nullable();
            $table->string('total_payout')->nullable();
            $table->string('total_advance')->nullable();
            $table->string('name');
            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
