<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WeekDays: int implements HasLabel
{
    case SATURDAY = 1;
    case SUNDAY = 2;
    case MONDAY = 3;
    case TUESDAY = 4;
    case WEDNESDAY = 5;
    case THURSDAY = 6;

    public function toArabic(): string
    {
        return match ($this) {
            self::SATURDAY => 'السبت',
            self::SUNDAY => 'الأحد',
            self::MONDAY => 'الاثنين',
            self::TUESDAY => 'الثلاثاء',
            self::WEDNESDAY => 'الأربعاء',
            self::THURSDAY => 'الخميس',
        };
    }
    public function getLabel(): string
    {
        return match ($this) {
            self::SATURDAY => 'السبت',
            self::SUNDAY => 'الأحد',
            self::MONDAY => 'الاثنين',
            self::TUESDAY => 'الثلاثاء',
            self::WEDNESDAY => 'الأربعاء',
            self::THURSDAY => 'الخميس',
        };
    }
}
