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
        Schema::table('access_requests', function (Blueprint $table) {
            $table->enum('request_type', ['new', 'renewal'])->default('new')->after('status');
            $table->enum('membership_type', ['monthly', 'quarterly'])->nullable()->after('request_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_requests', function (Blueprint $table) {
            $table->dropColumn(['request_type', 'membership_type']);
        });
    }
};
