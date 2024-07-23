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
        Schema::create('jenis_aktifitas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis_aktifitas')->unique();
            $table->string('nama_jenis_aktifitas');
            $table->enum('flag_tab_menu_personal', ['active', 'inactive'])->default('active');
            $table->integer('batas_waktu_kunjungan')->default(999);
            $table->integer('batas_maksimal_umur_progress')->default(999);
            $table->enum('status', [0, 1])->default(1);
            $table->timestampsWithUser();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_aktifitas');
    }
};
