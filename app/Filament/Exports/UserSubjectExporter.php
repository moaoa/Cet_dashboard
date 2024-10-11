<?php

namespace App\Filament\Exports;

use App\Models\UserSubject;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserSubjectExporter extends Exporter
{
    protected static ?string $model = UserSubject::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('passed')->label('نجح'),
            ExportColumn::make('subject.name')->label('اسم المادة'),
            ExportColumn::make('user.name')->label('اسم الطالب'),
            // ExportColumn::make('created_at')->label(''),
            // ExportColumn::make('updated_at')->label(''),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {   
        $body = 'Your user subject export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
