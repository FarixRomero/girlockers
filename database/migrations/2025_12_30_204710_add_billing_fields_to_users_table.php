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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('document_type', ['DNI', 'RUC'])->nullable()->after('email');
            $table->string('document_number', 20)->nullable()->after('document_type');
            $table->string('first_name', 100)->nullable()->after('document_number');
            $table->string('last_name', 100)->nullable()->after('first_name');
            $table->string('phone', 20)->nullable()->after('last_name');
            $table->string('country', 50)->default('Perú')->after('phone');
            $table->string('region', 100)->nullable()->after('country');
            $table->string('province', 100)->nullable()->after('region');
            $table->string('district', 100)->nullable()->after('province');
            $table->string('billing_address')->nullable()->after('district');
            $table->string('billing_reference')->nullable()->after('billing_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'document_type',
                'document_number',
                'first_name',
                'last_name',
                'phone',
                'country',
                'region',
                'province',
                'district',
                'billing_address',
                'billing_reference',
            ]);
        });
    }
};
