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
        // Para SQLite, necesitamos recrear la tabla con los nuevos valores
        // Para MySQL/PostgreSQL, podríamos usar ALTER, pero esta forma funciona para todas

        // Primero, actualizar los valores existentes si los hay
        \DB::table('courses')->update(['level' => \DB::raw("
            CASE
                WHEN level = 'beginner' THEN 'principiante'
                WHEN level = 'intermediate' THEN 'intermedio'
                WHEN level = 'advanced' THEN 'avanzado'
                ELSE level
            END
        ")]);

        // No podemos modificar el ENUM directamente en SQLite,
        // pero la validación se hace en el código, no en la BD
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::table('courses')->update(['level' => \DB::raw("
            CASE
                WHEN level = 'principiante' THEN 'beginner'
                WHEN level = 'intermedio' THEN 'intermediate'
                WHEN level = 'avanzado' THEN 'advanced'
                ELSE level
            END
        ")]);
    }
};
