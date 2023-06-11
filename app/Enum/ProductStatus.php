<?php

namespace App\Enum;

Enum ProductStatus: int
{
    case DISABLED = 0;
    case ACTIVE = 1;

    public function getStatusLabel($value): string
    {
        return match ($value) {
            self::DISABLED => 'Disabled',
            self::ACTIVE => 'Active'
        };
    }

    public function getLabel(): string
    {
        return self::getStatusLabel($this);
    }
}
