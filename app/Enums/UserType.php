<?php

namespace App\Enums;

enum UserType: int
{
    case Admin = 1;
    case Student = 2;
    case Teacher = 3;

    public  function label()
    {
        return match ($this)
        {
            UserType::Admin => 'مدير',
            UserType::Student => 'طالب',
            UserType::Teacher => 'استاذ',
            default => 'غير معروف'
        };
    }
}
