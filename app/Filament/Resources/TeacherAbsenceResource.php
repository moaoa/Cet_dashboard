<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceStatus;
use App\Enums\Major;
use App\Filament\Resources\TeacherAbsenceResource\Pages;
use App\Filament\Resources\TeacherAbsenceResource\RelationManagers;
use App\Models\Group;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\TeacherAbsence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Label;

class TeacherAbsenceResource extends Resource
{
    protected static ?string $model = TeacherAbsence::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationLabel = 'غياب الاستاذ';
    protected static ?string $navigationGroup = 'الاستاذ';
    protected static ?int $navigationSort = 11;

    public static function getModelLabel(): string
    {
        return 'غياب استاذ'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'غياب اساتذة'; // Directly writing the translation for "Users"
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lecture.teacher.name')
                    ->label('اسم الأستاذ') // "Teacher Name"
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة') // "Status"
                    ->formatStateUsing(fn($state) => AttendanceStatus::from($state)->getLabel()),
                Tables\Columns\TextColumn::make('date')
                    ->label('التاريخ') // "Date"
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecture.subject.name')
                    ->label('المادة') // "Subject"
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء') // "Created At"
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث') // "Updated At"
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\Select::make('Major')
                            ->options(Major::class)
                            ->label('التخصص')
                            ->live(),
                        Forms\Components\Select::make('semester_id')
                            ->options(fn(Forms\Get $get) => Semester::where(
                                'major',
                                $get('Major')
                            )->pluck('name', 'id'))
                            ->Label('الفصل الدراسي')
                            ->live(),
                        Forms\Components\Select::make('subject_id')
                            ->options(
                                fn(Forms\Get $get) => Subject::where(
                                    'semester_id',
                                    $get('semester_id')
                                )->pluck('name', 'id')
                            )
                            ->label('المادة الدراسية')
                            ->live(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->whereHas('lecture', function (Builder $query) use ($data) {
                                if ($data['subject_id'] == null) return $query;
                                $query->where('subject_id', $data['subject_id']);
                            });
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeacherAbsences::route('/'),
            // 'create' => Pages\CreateTeacherAbsence::route('/create'),
            // 'edit' => Pages\EditTeacherAbsence::route('/{record}/edit'),
        ];
    }
}
