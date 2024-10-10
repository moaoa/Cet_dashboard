<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserSubjectResource\Pages;
use App\Filament\Resources\UserSubjectResource\RelationManagers;
use App\Models\UserSubject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserSubjectResource extends Resource
{
    protected static ?string $model = UserSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'ادارة مواد الطالب';

    public static function getModelLabel(): string
    {
        return 'مواد طالب'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'مواد الطلبة '; // Directly writing the translation for "Users"
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('الطالب') // Translated label for "User"
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->label('المادة') // Translated label for "Subject"
                    ->required(),
                Forms\Components\Toggle::make('passed')
                    ->label('نجح') // Translated label for "Passed"
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->label('الطالب'), // Translated label for "User"
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable()
                    ->label('المادة'), // Translated label for "Subject"
                Tables\Columns\IconColumn::make('passed')
                    ->boolean()
                    ->label('نجح'), // Translated label for "Passed"
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ الإنشاء'), // Translated label for "Created At"
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('تاريخ التحديث'), // Translated label for "Updated At"
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
            'index' => Pages\ListUserSubjects::route('/'),
            'create' => Pages\CreateUserSubject::route('/create'),
            'edit' => Pages\EditUserSubject::route('/{record}/edit'),
        ];
    }
}
