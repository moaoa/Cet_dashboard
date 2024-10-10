<?php

namespace App\Filament\Widgets;

use App\Enums\Major;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use App\Filament\Widgets\getNumberOfStundentsInMajor;

class ChartMajor extends ChartWidget
{



    protected static ?string $heading = 'عدد الطلاب في الأقسام';

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
                    //
                    // ],
                    //
                ],
                //
            ],
            //'height' => 300,
            'labels' => ["العام", "الحاسب ألي ", "الاتصالات", "تحكم الألي"],

        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
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
            'backgroundColor' => 'rgb(255, 255, 255)',
        ];
    }

    protected function getContainerStyle(): string
    {
        return 'display: inline;margin: 0 auto;'; // Apply inline style to center the widget
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getBackgroundColor(): string
    {
        return 'rgb(255, 255, 255)'; // Set the background color to white
    }
    protected function getNumberOfStundentsInMajor($majorId)
    {
        $numberOfStudents = DB::table('groups')
            ->join('group_user', 'groups.id', '=', 'group_user.group_id')

            ->join('semesters', 'semesters.id', '=', 'groups.semesters_id')

            ->where('semesters.major', $majorId)
            ->count();
        return $numberOfStudents;
    }
}
