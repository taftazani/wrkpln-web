<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_code')->unique();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('address');
            $table->string('postal_code');
            $table->integer('user_count');
            $table->unsignedBigInteger('package_type_id');
            $table->timestamps();
            $table->foreign('package_type_id')->references('id')->on('package_types')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('user_id')->unique()->after('id');
            $table->unsignedBigInteger('company_id')->nullable()->after('id');
            $table->boolean('is_super_admin')->default(false)->after('company_id');

            // Foreign key constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::dropIfExists('companies');
    }
};