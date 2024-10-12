<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('المادة'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('associate')
                    ->label('ربط مادة')
                    ->modalHeading('المواد')
                    ->modalWidth('xl')
                    ->action(function (array $data) {
                        $record = $this->getOwnerRecord();
                        $record->subjects()->syncWithoutDetaching($data['subject']);
                    })
                    ->modalHeading('إضافة مجموعة')
                    ->form([
                        Forms\Components\Select::make('subject')
                            ->options(Subject::where(
                                'semester_id',
                                $this->ownerRecord->semester_id
                            )->pluck('name', 'id'))
                            ->live()
                            ->required()
                            ->label('المواد'),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->label('حذف المادة من المجموعة'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
