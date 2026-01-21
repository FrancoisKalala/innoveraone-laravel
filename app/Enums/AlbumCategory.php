<?php

namespace App\Enums;

enum AlbumCategory: string
{
    case PHOTOGRAPHY = 'Photography';
    case ART = 'Art';
    case MUSIC = 'Music';
    case TRAVEL = 'Travel';
    case FOOD = 'Food';
    case FASHION = 'Fashion';
    case NATURE = 'Nature';
    case ARCHITECTURE = 'Architecture';
    case SPORTS = 'Sports';
    case TECHNOLOGY = 'Technology';
    case PETS = 'Pets';
    case FAMILY = 'Family';
    case EVENTS = 'Events';
    case BUSINESS = 'Business';
    case OTHER = 'Other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->value;
        }
        return $options;
    }
}
