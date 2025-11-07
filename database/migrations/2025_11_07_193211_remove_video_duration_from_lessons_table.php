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
            // Drop video_duration column - we now use 'duration' for everything (in seconds)
            $table->dropColumn('video_duration');

            // Update duration column comment to clarify it's in seconds
            $table->integer('duration')->default(0)->comment('Duration in seconds')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Restore video_duration column
            $table->integer('video_duration')->nullable()->after('bunny_video_id')->comment('Duration in seconds');

            // Remove comment from duration (restore original state)
            $table->integer('duration')->default(0)->change();
        });
    }
};
