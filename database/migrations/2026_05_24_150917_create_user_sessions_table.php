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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('refresh_token', 500);
            $table->string('device_name', 200);
            $table->string('ip_address', 200);
            $table->string('user_agent', 200);
            $table->unsignedBigInteger('refresh_token_expires_at');
            $table->unsignedBigInteger('last_login_at')->nullable();
            $table->boolean('is_logged_in')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
