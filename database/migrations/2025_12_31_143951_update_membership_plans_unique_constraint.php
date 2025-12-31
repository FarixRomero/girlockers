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
        Schema::table('membership_plans', function (Blueprint $table) {
            // Eliminar el índice único del campo 'type'
            $table->dropUnique('membership_plans_type_unique');

            // Crear índice único compuesto para type + currency
            $table->unique(['type', 'currency'], 'membership_plans_type_currency_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            // Eliminar el índice único compuesto
            $table->dropUnique('membership_plans_type_currency_unique');

            // Restaurar el índice único del campo 'type'
            $table->unique('type', 'membership_plans_type_unique');
        });
    }
};
