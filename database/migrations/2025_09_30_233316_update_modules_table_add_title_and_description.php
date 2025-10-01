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
        Schema::table('modules', function (Blueprint $table) {
            // Rename 'name' to 'title'
            $table->renameColumn('name', 'title');
            // Add 'description' column
            $table->text('description')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            // Remove 'description' column
            $table->dropColumn('description');
            // Rename 'title' back to 'name'
            $table->renameColumn('title', 'name');
        });
    }
};
