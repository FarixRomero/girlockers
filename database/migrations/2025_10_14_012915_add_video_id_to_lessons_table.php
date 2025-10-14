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
        Schema::table('lessons', function (Blueprint $table) {
            // Agregar soporte para Bunny.net videos
            $table->string('bunny_video_id')->nullable()->after('description');
            $table->integer('video_duration')->nullable()->after('bunny_video_id')->comment('Duration in seconds');
        });

        // Modificar el enum video_type para incluir 'bunny'
        \DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('youtube', 'local', 'bunny') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['bunny_video_id', 'video_duration']);
        });

        // Revertir el enum video_type
        \DB::statement("ALTER TABLE lessons MODIFY COLUMN video_type ENUM('youtube', 'local') NOT NULL");
    }
};
