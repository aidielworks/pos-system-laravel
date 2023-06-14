<?php

namespace App\Enum;

enum PaymentType: int
{
    case CASH = 0;
    case ONLINE = 1;

    public function getStatusLabel($value): string
    {
        return match ($value) {
            self::CASH => 'Cash',
            self::ONLINE => 'Online'
        };
    }

    public function getLabel(): string
    {
        return $this->getStatusLabel($this);
    }
}
