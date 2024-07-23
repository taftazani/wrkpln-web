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
        Schema::create('menu_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_type_id');
            $table->enum('module',['web', 'mobile'])->default('web')->comment('Module Choices');
            $table->unsignedBigInteger('permission_id');
            $table->enum('status',[0, 1])->default(1)->comment('Active Status');
            $table->timestamps();
            $table->foreign('package_type_id')->references('id')->on('package_types')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_mappings');
    }
};
