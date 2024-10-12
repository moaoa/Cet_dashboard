<?php

namespace App\Filament\Resources;

use App\Enums\Major;
use App\Filament\Resources\SubjectResource\Pages;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Models\Semester;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'ادارة المواد الدراسية';
    protected static ?string $navigationGroup = 'عام';
    protected static ?int $navigationSort = 3;
    
    public static function getModelLabel(): string
    {
        return 'مادة'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'مواد'; // Directly writing the translation for "Users"
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم المادة')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('Major')
                    ->options(Major::class)
                    ->enum(Major::class)
                    ->label('القسم')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn(callable $set) => $set('semester_id', null)),
                Forms\Components\Select::make('semester_id')
                    ->label('الفصل الدراسي')
                    ->options(function (callable $get) {
                        $majorValue = $get('Major');
                        if ($majorValue === null) {
                            return [];
                        }
                        $major = Major::from($majorValue);
                        return Semester::where('major', $major->value)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => $get('Major') === null)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state === null) {
                            return;
                        }
                        $set('semester_id', $state);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المادة')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('الفصل الدراسي')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.major')
                    ->label('القسم')
                    ->searchable()
                    ->formatStateUsing(fn($state) => Major::getArabicName($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
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
                        // Forms\Components\Select::make('subject_id')
                        //     ->options(
                        //         fn(Forms\Get $get) => Subject::where(
                        //             'semester_id',
                        //             $get('semester_id')
                        //         )->pluck('name', 'id')
                        //     )
                        //     ->label('المادة الدراسية')
                        //     ->live(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['semester_id']) && $data['semester_id'] !== null) {
                            $query->where('semester_id', $data['semester_id']);
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
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
