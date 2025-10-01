<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero agregar la columna sin índice único
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Generar slugs para cursos existentes
        $courses = DB::table('courses')->get();
        foreach ($courses as $course) {
            $slug = Str::slug($course->title);
            $originalSlug = $slug;
            $counter = 1;

            // Si el slug ya existe, agregar un número
            while (DB::table('courses')->where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            DB::table('courses')->where('id', $course->id)->update(['slug' => $slug]);
        }

        // Ahora hacer la columna única y no nulable
        Schema::table('courses', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
