<?php

namespace App\Filament\Widgets;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class card extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            stat::make('عدد الطلاب', User::count())
                ->description('العدد الإجمالي للطلاب في الكلية')->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3]),
            stat::make('عدد الأساتذة', Teacher::count())
                ->description('العدد الإجمالي للأساتذة في الكلية')->descriptionIcon('heroicon-o-pencil')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3]),
            stat::make('عدد المواد', Subject::count())
                ->description('العدد الإجمالي للمواد في الكلية')->descriptionIcon('heroicon-o-check-circle')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3]),

        ];
    }
}
