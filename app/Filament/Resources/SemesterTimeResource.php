<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterTimeResource\Pages;
use App\Filament\Resources\SemesterTimeResource\RelationManagers;
use App\Models\SemesterTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SemesterTimeResource extends Resource
{
    protected static ?string $model = SemesterTime::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('semester_start_date')
                    ->required(),
                Forms\Components\DatePicker::make('semester_end_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester_start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester_end_date')
                    ->date()
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
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSemesterTimes::route('/'),
            'create' => Pages\CreateSemesterTime::route('/create'),
            'edit' => Pages\EditSemesterTime::route('/{record}/edit'),
        ];
    }
}
