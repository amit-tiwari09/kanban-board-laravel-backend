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
        Schema::create('columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards', 'id')->cascadeOnDelete();
            $table->string('uuid', 50)->unique();
            $table->string('name', 300);
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_locked')->default(0);
            $table->boolean('is_archived')->default(0);
            $table->unsignedInteger('cards_limit')->nullable();
            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('last_updated_at')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
