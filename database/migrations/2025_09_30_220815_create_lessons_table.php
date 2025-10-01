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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('video_type', ['youtube', 'local']);
            $table->string('youtube_id', 20)->nullable();
            $table->string('local_path', 500)->nullable();
            $table->string('thumbnail', 500)->nullable();
            $table->boolean('is_trial')->default(false);
            $table->integer('order')->default(0);
            $table->integer('likes_count')->default(0);
            $table->timestamps();

            $table->index(['module_id', 'order']);
            $table->index(['module_id', 'is_trial']);
            $table->index('is_trial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
