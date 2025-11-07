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
            $table->string('country_code', 10)->nullable()->after('membership_type');
            $table->string('phone_number', 20)->nullable()->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('access_requests', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'phone_number']);
        });
    }
};
