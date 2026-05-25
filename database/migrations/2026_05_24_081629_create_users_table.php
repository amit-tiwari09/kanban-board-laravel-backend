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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->unique();
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('email', 200)->unique();
            $table->unsignedBigInteger('email_verified_at');
            $table->string('username', 100)->unique();
            $table->unsignedBigInteger('phone_number');
            $table->string('country_code', 10);
            $table->string('country', 100)->nullable();
            $table->unsignedBigInteger('phone_number_verified_at');
            $table->string('password', 200);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('registered_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
