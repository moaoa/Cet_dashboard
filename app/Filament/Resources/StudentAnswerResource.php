<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentAnswerResource\Pages;
use App\Filament\Resources\StudentAnswerResource\RelationManagers;
use App\Models\StudentAnswer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentAnswerResource extends Resource
{
    protected static ?string $model = StudentAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                Forms\Components\TextInput::make('answer')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quiz_question_student_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('answer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quiz_question_student_id')
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
            'index' => Pages\ListStudentAnswers::route('/'),
            'create' => Pages\CreateStudentAnswer::route('/create'),
            'edit' => Pages\EditStudentAnswer::route('/{record}/edit'),
        ];
    }
}
