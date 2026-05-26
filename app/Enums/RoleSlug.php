<?php

namespace App\Enums;

enum RoleSlug: string
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN = 'admin';
    case STAFF_GUDANG = 'staff-gudang';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::STAFF_GUDANG => 'Staff Gudang',
        };
    }
}
