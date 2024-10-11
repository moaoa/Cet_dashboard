<?php

namespace App\Filament\Widgets;

use App\Enums\Major;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use App\Filament\Widgets\getNumberOfStundentsInMajor;
use App\Models\User;

class ChartMajor extends ChartWidget
{



    protected static ?string $heading = 'عدد الطلبة في جميع الأقسام';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'عدد الطلاب',
                    'data' => [
                        ChartMajor::getNumberOfStundentsInMajor(Major::GENERAL),
                        ChartMajor::getNumberOfStundentsInMajor(Major::COMPUTER),
                        ChartMajor::getNumberOfStundentsInMajor(Major::COMUNICATION),
                        ChartMajor::getNumberOfStundentsInMajor(Major::POWER)
                    ],
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                    ],

                ],

            ],
            'labels' => ["العام", "الحاسب ألي ", "الاتصالات", "تحكم الألي"],
            //'description' => 'عدد الطلاب في الأقسام',


        ];
    }


    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => 'top',

                ],

            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
            'scales' => [
                'y' => [
                    'display' => false,
                ],
                'x' => [
                    'display' => false,
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'doughnut';
    }


    protected function getNumberOfStundentsInMajor($majorId)
    {

        $studentCount = User::whereHas('groups', function ($query) use ($majorId) {
            $query->whereHas('semester', function ($q) use ($majorId) {
                $q->where('major', $majorId);
            });
        })->count();
        return $studentCount;
    }
}
