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
        Schema::create('detil_bulk_upload_organizations', function (Blueprint $table) {
            $table->id();
            $table->string("baris_kesalahan")->nullable();
            $table->string("penjelasan")->nullable();
            $table->foreignId('bulk_upload_organizations_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detil_bulk_upload_organizations');
    }
};
