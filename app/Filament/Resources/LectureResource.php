<?php

namespace App\Filament\Resources;


use App\Enums\WeekDays;
use App\Filament\Resources\LectureResource\Pages;
use App\Filament\Resources\LectureResource\RelationManagers;
use App\Models\ClassRoom;
use App\Models\Group;
use App\Models\Lecture;
use App\Models\Teacher;
use App\Models\User;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TimePicker::make('start_time')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->required(),
                Forms\Components\Select::make('day_of_week')
                    ->required()
                    ->live()
                    ->options(WeekDays::class),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
                Forms\Components\Select::make('class_room_id')
                    ->relationship('classRoom', 'name')
                    ->options(function (Forms\Get $get) {
                        $start_time = (string)$get('start_time');
                        $end_time = (string)$get('end_time');
                        $day_of_week = (int)$get('day_of_week');

                        if ($start_time == null || $end_time == null || $day_of_week == null) {
                            return [];
                        }

                        $lecturesInTimeRange = Lecture::where('day_of_week', $day_of_week)
                            ->where('start_time', '<=', $start_time)
                            ->where('end_time', '>', $start_time)
                            ->orWhere(function ($query) use ($end_time) {
                                $query->where('start_time', '>', $end_time)
                                    ->where('end_time', '<=', $end_time);
                            })
                            ->get();


                        $availableClassrooms = ClassRoom::whereNotIn(
                            'id',
                            $lecturesInTimeRange->pluck('class_room_id')
                        )->pluck('name', 'id')->toArray();

                        // dd($availableClassrooms);
                        return $availableClassrooms;
                    })
                    ->required(),
                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'name')
                    ->options(Group::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('teacher_id')
                    ->label('الأستاذ')
                    ->options(fn(Forms\Get $get) => $get('start_time'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_of_week')
                    ->formatStateUsing(fn($state) => WeekDays::from($state)->toArabic()),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('classRoom.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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

    public static function getAvailableClassRooms($start_time, $end_time, $day_of_week)
    {
        if ($start_time == null || $end_time == null || $day_of_week == null) {
            return [];
        }

        $lecturesInTimeRange = Lecture::where('day_of_week', $day_of_week)
            ->where('start_time', '<=', $start_time)
            ->where('end_time', '>', $start_time)
            ->orWhere(function ($query) use ($end_time) {
                $query->where('start_time', '>', $end_time)
                    ->where('end_time', '<=', $end_time);
            })
            ->get();


        $availableClassrooms = ClassRoom::whereNotIn(
            'id',
            $lecturesInTimeRange->pluck('class_room_id')
        )
            ->pluck('name', 'id');

        // dd($availableClassrooms);
        return $availableClassrooms;
    }
}
