<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeworkResource\Pages;
use App\Filament\Resources\HomeworkResource\RelationManagers;
use App\Infolists\Components\AttachmentsEntry;
use App\Models\Homework;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class HomeworkResource extends Resource
{
    protected static ?string $model = Homework::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'الواجبات الدراسية';
    protected static ?string $navigationGroup = 'عام';
    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return 'واجب'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'واجبات'; // Directly writing the translation for "Users"
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('attachments')
                    ->required(),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name')
                    ->required(),
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.name')
                    ->label('العنوان')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('اسم الاستاذ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('اسم المادة')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class
        ];
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('العنوان'),
                TextEntry::make('teacher.name')->label('اسم الاستاذ'),
                TextEntry::make('subject.name')->label('اسم المادة'),
                TextEntry::make('created_at')->label('تاريخ النشر'),
                // TextEntry::make('updated_at')->label(''),
                AttachmentsEntry::make('attachments')->label('الملحقات'),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomework::route('/'),
            'create' => Pages\CreateHomework::route('/create'),
            'edit' => Pages\EditHomework::route('/{record}/edit'),
            'view' => Pages\ViewHomework::route('/{record}'),
        ];
    }
}
