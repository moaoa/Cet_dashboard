<?php

namespace App\Filament\Resources;


use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Group;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'ادارة الطلبة';
    protected static ?string $navigationGroup = 'الطالب';
    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'الطالب'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'الطلبة'; // Directly writing the translation for "Users"
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الطالب') // "Student Name"
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('ref_number')
                //     ->label('رقم القيد') // "Reference Number"
                //     ->required()
                //     ->numeric(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('كلمة المرور') // "Password"
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني') // "Email"
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->label('رقم الهاتف') // "Phone Number"
                    ->tel()
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ref_number')
                ->label('رقم القيد') // "Reference Number"
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الطالب') // "Student Name"
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني') // "Email"
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('رقم الهاتف') // "Phone Number"
                    ->searchable(),
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

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         RelationManagers\GroupsRelationManager::class,
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
