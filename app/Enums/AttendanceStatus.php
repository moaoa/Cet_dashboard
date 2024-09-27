<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AttendanceStatus: int implements HasLabel
{
    case Absent = 1;
    case Present = 2;
    case AbsentWithPermission = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Absent => 'غائب',
            self::Present => 'موجود',
            self::AbsentWithPermission => 'غائب مع إذن',
            default => 'غير معروف',
        };
    }
}
