<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\OneSignalNotifier;
use App\Mail\CallUserEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;



class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'ادارة الاستاذ';
    protected static ?string $navigationGroup = 'الاستاذ';
    protected static ?int $navigationSort = 9;

    public static function getModelLabel(): string
    {
        return 'استاذ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'أساتذة'; // Directly writing the translation for "Users"
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الأستاذ') // "Teacher Name"
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ref_number')
                    ->label('رقم التوظيف') // "Reference Number"
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور') // "Password"
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
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
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ref_number')
                    ->label('رقم التوظيف') // "Reference Number"
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الأستاذ') // "Teacher Name"
                    ->searchable(), // Making the name searchable
                // Making the reference number searchable
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
                Action::make('manage')
                    ->label("استدعاء")
                    ->modalHeading(fn(Model $record) => 'إستدعاء للاستاذ :' . $record->name)
                    ->form([
                        Forms\Components\TextInput::make('description')
                            ->required()
                            ->label('النص'),
                    ])
                    ->action(function (Teacher $teacher, array $data) {
                        OneSignalNotifier::init();

                        OneSignalNotifier::sendNotificationToUsers(
                            json_decode($teacher->device_subscriptions),
                            $data['description']
                        );

                        Mail::to($teacher->email)->send(new CallUserEmail($data['description']));

                        Notification::make()
                            ->title('تم ارسال الاستدعاء')
                            ->success()
                            ->send();
                    })
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
            RelationManagers\GroupsRelationManager::class,
            RelationManagers\SubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
