<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Major: int implements HasLabel
{
    case GENERAL = 0;
    case COMPUTER = 1;
    case POWER = 2;
    case COMUNICATION = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::GENERAL => 'عام',
            self::COMPUTER => 'حاسب الي',
            self::POWER => 'تحكم الي',
            self::COMUNICATION => 'اتصالات',
            default => 'غير معروف',
        };
    }

    public static function getArabicName(int $major): string
    {
        return match ($major) {
            self::GENERAL->value => 'عام',
            self::COMPUTER->value => 'حاسب الي',
            self::POWER->value => 'تحكم الي',
            self::COMUNICATION->value => 'اتصالات',
            default => 'غير معروف',
        };
    }
}
