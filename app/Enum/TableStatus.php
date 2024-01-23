<?php

namespace App\Enum;

enum TableStatus: int
{
    case NOT_AVAILABLE = 0;
    case AVAILABLE = 1;
    case RESERVED = 2;

    public function getStatusLabel($value): string
    {
        return match ($value) {
            self::NOT_AVAILABLE => 'Not Available',
            self::AVAILABLE => 'Available',
            self::RESERVED => 'Reserved'
        };
    }

    public function getLabel(): string
    {
        return self::getStatusLabel($this);
    }
}
