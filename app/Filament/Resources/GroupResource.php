<?php

namespace App\Filament\Resources;

use App\Enums\Major;
use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'ادارة المجموعات';
    protected static ?string $navigationGroup = 'عام';
    
    public static function getModelLabel(): string
    {
        return 'مجموعة'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'مجموعات'; // Directly writing the translation for "Users"
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('major')
                    ->label('التخصص')
                    ->required()
                    ->live()
                    ->options(Major::class),
                Forms\Components\Select::make('semester_id')
                    ->label('الفصل الدراسي')
                    ->relationship('semester', 'name')
                    ->options(function (Forms\Get $get) {
                        $semesters = Semester::where('major', $get('major'))->pluck('name', 'id');
                        return $semesters;
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('المجموعة'),
                Tables\Columns\TextColumn::make('semester.name')
                    ->searchable()
                    ->label('الفصل الدراسي'),
                Tables\Columns\TextColumn::make('semester.major')
                    ->searchable()
                    ->label('التخصص')
                    ->getStateUsing(function ($record) {
                        return Major::getArabicName($record->semester->major);
                    }),
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
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
