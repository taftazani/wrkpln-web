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
        Schema::create('bulk_upload_organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daftar_no_urut')->nullable();
            $table->string('nama_excel_upload')->nullable();
            $table->string('jumlah_data')->nullable();
            $table->string('data_berhasil')->nullable();
            $table->string('data_gagal')->nullable();
            $table->enum('status', ['Success', 'Failed'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulk_upload_organizations');
    }
};
