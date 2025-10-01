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
        // Cambiar temporalmente la columna a VARCHAR
        DB::statement("ALTER TABLE courses MODIFY COLUMN level VARCHAR(50)");

        // Actualizar los valores existentes
        DB::statement("UPDATE courses SET level = 'principiante' WHERE level = 'beginner'");
        DB::statement("UPDATE courses SET level = 'intermedio' WHERE level = 'intermediate'");
        DB::statement("UPDATE courses SET level = 'avanzado' WHERE level = 'advanced'");

        // Cambiar a enum con los nuevos valores
        DB::statement("ALTER TABLE courses MODIFY COLUMN level ENUM('principiante', 'intermedio', 'avanzado') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cambiar temporalmente la columna a VARCHAR
        DB::statement("ALTER TABLE courses MODIFY COLUMN level VARCHAR(50)");

        // Revertir los valores
        DB::statement("UPDATE courses SET level = 'beginner' WHERE level = 'principiante'");
        DB::statement("UPDATE courses SET level = 'intermediate' WHERE level = 'intermedio'");
        DB::statement("UPDATE courses SET level = 'advanced' WHERE level = 'avanzado'");

        // Revertir el enum
        DB::statement("ALTER TABLE courses MODIFY COLUMN level ENUM('beginner', 'intermediate', 'advanced') NOT NULL");
    }
};
