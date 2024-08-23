<?php

namespace App\Enums;

enum AttendanceStatus: int
{
    case Absent = 1;
    case Present = 2;
    case AbsentWithPermission = 3;
}
