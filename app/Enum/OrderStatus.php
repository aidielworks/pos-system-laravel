<?php

namespace App\Enum;

enum OrderStatus: int
{
    case UNPAID = 0;
    case PAID = 1;

    public function getStatusLabel($value): string
    {
        return match ($value) {
            self::UNPAID => 'UNPAID',
            self::PAID => 'PAID'
        };
    }

    public function getLabel(): string
    {
        return $this->getStatusLabel($this);
    }
}

