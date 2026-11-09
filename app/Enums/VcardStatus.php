<?php

namespace App\Enums;

enum VcardStatus: int
{
    case Active = 1;
    case Inactive = 2;

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Inactive => 'Tidak Aktif',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Active => 'text-bg-success',
            self::Inactive => 'text-bg-danger',
        };
    }
}
