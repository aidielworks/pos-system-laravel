<?php

namespace App\Enum;

enum MealType: int
{
    case FOOD = 0;
    case DRINKS = 1;

    public function getStatusLabel($value): string
    {
        return match ($value) {
            self::FOOD => 'Food',
            self::DRINKS => 'Drinks'
        };
    }

    public function getLabel(): string
    {
        return self::getStatusLabel($this);
    }
}
