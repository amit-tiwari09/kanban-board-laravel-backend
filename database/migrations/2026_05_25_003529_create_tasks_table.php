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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->unique();
            $table->string('task_code', 50)->unique();
            $table->foreignId('column_id')->constrained('columns', 'id')->cascadeOnDelete();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->foreignId('cover_image_id')->nullable()->constrained('files', 'id')
                ->nullOnDelete();

            $table->unsignedTinyInteger('priority');
            $table->unsignedInteger('position');
            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('start_date')->nullable();
            $table->unsignedBigInteger('due_date')->nullable();
            $table->unsignedBigInteger('completed_at')->nullable();
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_completed')->default(false);
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
