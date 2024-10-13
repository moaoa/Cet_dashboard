<?php

namespace App\Filament\Resources;

use App\Enums\Major;
use App\Enums\WeekDays;
use App\Filament\Resources\LectureResource\Pages;
use App\Filament\Resources\LectureResource\RelationManagers;
use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\Lecture;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LectureResource extends Resource
{
    protected static ?string $model = Lecture::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationLabel = 'ادارة المحاضرات';
    protected static ?string $navigationGroup = 'عام';
    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return 'محاضرة'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'محاضرات'; // Directly writing the translation for "Users"
    }

    public static function getAvailableClassRooms($start_time, $end_time, $day_of_week)
    {
        $lecturesInTimeRange = Lecture::where('day_of_week', $day_of_week)
            ->where(function ($query) use ($start_time, $end_time) {
                $query->where(function ($q) use ($start_time, $end_time) {
                    $q->where('start_time', '<', $end_time)
                        ->where('end_time', '>', $start_time);
                });
            })
            ->get();

        $availableClassrooms = ClassRoom::whereNotIn(
            'id',
            $lecturesInTimeRange->pluck('class_room_id')
        )
            ->pluck('name', 'id');

        return $availableClassrooms;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('start_time')
                    ->options([
                        '08:00' =>  '08:00',
                        '09:00' =>     '09:00',
                        '10:00' =>     '10:00',
                        '11:00' =>     '11:00',
                        '12:00' =>     '12:00',
                        '13:00' =>     '13:00',
                        '14:00' =>     '14:00',
                        '15:00' =>     '15:00',
                        '16:00' =>     '16:00',
                        '17:00' =>     '17:00',

                    ])
                    ->required()
                    ->label('وقت البداية'),

                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->minLength(1)
                    ->maxLength(6)
                    ->required()
                    ->label('مدة المحاضرة'), // "Duration"

                Forms\Components\Select::make('Major')
                    ->options(Major::class)
                    ->required()
                    ->label('التخصص')->live(), // "Major"

                Forms\Components\select::make('semester_id')
                    ->options(fn(Forms\Get $get) => Semester::where(
                        'major',
                        $get('Major')
                    )->pluck('name', 'id'))
                    ->required()
                    ->label('الفصل الدراسي')->live(),

                Forms\Components\Select::make('subject_id')
                    ->options(fn(Forms\Get $get) => Subject::where(
                        'semester_id',
                        $get('semester_id')
                    )->pluck('name', 'id'))
                    ->required()
                    ->label('المادة') // "Subject"
                    ->reactive()->live(),

                Forms\Components\Select::make('day_of_week')
                    ->required()
                    ->live()
                    ->options(WeekDays::class)
                    ->label('يوم الأسبوع'), // "Day of the Week"

                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->options(function (Forms\Get $get) {
                        $start_time = (string) $get('start_time');
                        $duration = (int) $get('duration');
                        $day_of_week = (int) $get('day_of_week');

                        if ($start_time == null || $duration == null || $day_of_week == null) {
                            return [];
                        }

                        // Calculate end_time based on the duration
                        $end_time = date('H:i', strtotime("+$duration hours", strtotime($start_time)));

                        // Fetch available classrooms
                        return LectureResource::getAvailableClassRooms($start_time, $end_time, $day_of_week);
                    })
                    ->required()
                    ->label('مكان المحاضرة'), // "Class Room"

                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'name')
                    ->multiple()
                    ->options(function (Forms\Get $get) {
                        $semester_id = $get('semester_id');

                        if ($semester_id == null) {
                            return [];
                        }

                        return Group::where('semester_id', $semester_id)
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->label('المجموعة') // "Group"
                    ->reactive(),

                Forms\Components\Select::make('teacher_id')
                    ->options(function (Forms\Get $get) {
                        $subject_id = $get('subject_id');
                        $group_id = $get('group_id');

                        if ($subject_id == null || $group_id == null) {
                            return [];
                        }

                        return Teacher::whereHas('subjects', function ($query) use ($subject_id) {
                            $query->where('subject_id', $subject_id);
                        })
                            ->whereHas('groups', function ($query) use ($group_id) {
                                $query->where('group_id', $group_id);
                            })
                            ->pluck('name', 'id');
                    })
                    ->label('الأستاذ')
                    ->required()
                    ->reactive(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('day_of_week')
                    ->formatStateUsing(fn($state) => WeekDays::from($state)->toArabic())
                    ->label('يوم '), // "Day of the Week"
                Tables\Columns\TextColumn::make('start_time')

                    ->time()
                    ->sortable()
                    ->dateTime()->formatStateUsing(fn($state) => $state->format('H:i'))
                    ->label('وقت البدء'), // "Start Time"
                Tables\Columns\TextColumn::make('end_time')
                    ->time()
                    ->sortable()
                    ->dateTime()->formatStateUsing(fn($state) => $state->format('H:i'))
                    ->label('وقت الانتهاء'), // "End Time"
                Tables\Columns\TextColumn::make('teacher.name')
                    ->searchable()
                    ->label('الأستاذ'), // "Teacher"

                Tables\Columns\TextColumn::make('subject.name')
                    ->searchable()
                    ->label('المادة'), // "Subject"
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->searchable()
                    ->label('القاعة'), // "Class Room"
                Tables\Columns\TextColumn::make('group.name')
                    ->label('المجموعة'), // "Group"
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ الإنشاء'), // "Created At"
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ التحديث'), // "Updated At"
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
                        if (isset($data['subject_id']) && $data['subject_id'] !== null) {
                            $query->where('subject_id', $data['subject_id']);
                        }
                        return $query;
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLectures::route('/'),
            'create' => Pages\CreateLecture::route('/create'),
            'edit' => Pages\EditLecture::route('/{record}/edit'),
        ];
    }
}
