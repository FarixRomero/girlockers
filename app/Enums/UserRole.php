<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case Admin = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Student => 'Estudiante',
            self::Admin => 'Administrador',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isStudent(): bool
    {
        return $this === self::Student;
    }
}
