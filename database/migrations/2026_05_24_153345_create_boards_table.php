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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->unique();
            $table->foreignId('workspace_id')
                ->constrained('workspaces', 'id')
                ->cascadeOnDelete();

            $table->string('name', 300);
            $table->text('description')->nullable();
            $table->foreignId('cover_image_id')
            ->nullable()
                ->constrained('files', 'id')
                ->nullOnDelete();

            $table->unsignedBigInteger('created_at');
            $table->unsignedBigInteger('created_by');
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('visibility')->default(0);
            $table->unsignedTinyInteger('access_level')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
