<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Hcs = 'hcs';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Hcs => 'Tim HCS',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin => 'text-bg-primary',
            self::Hcs => 'text-bg-secondary',
        };
    }
}

