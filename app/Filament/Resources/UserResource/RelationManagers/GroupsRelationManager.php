<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\Major;
use App\Models\Group;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class GroupsRelationManager extends RelationManager
{
    protected static string $relationship = 'groups';

    protected static ?string $Label = 'المجموعات';

    public static function getModelLabel(): string
    {
        return 'الطالب'; // Directly writing the translation for "User"
    }

    public static function getPluralModelLabel(): string
    {
        return 'الطلبة'; // Directly writing the translation for "Users"
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('اسم المجموعة')
                    ->default($this->getRecord()->name ?? ''),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المجموعة'),
                Tables\Columns\TextColumn::make('semester.major')
                    ->label('القسم')
                    ->getStateUsing(function ($record) {
                        return Major::from($record->semester->major); // Assuming your enum has a label method
                    })
                    ->searchable(), // Enable searching if needed
                Tables\Columns\TextColumn::make('semester.name')
                    ->label('الفصل الدراسي')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('associate')
                    ->label('اضافة الى مجموعة')
                    ->modalHeading('إضافة مجموعة')
                    ->modalWidth('xl')
                    ->action(function (array $data) {
                        $record = $this->getOwnerRecord();
                        // dd($record);
                        $groupId = $data['groups'];

                        // Check if the group already exists in the user's groups
                        if ($record->groups()->where('group_id', $groupId)->exists()) {
                            Notification::make()
                                ->title('المجموعة موجودة بالفعل!')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Get the semester_id of the current group the user is in
                        $currentGroupSem = Group::where('id', $groupId)->first()?->semester_id;
                        $recordGroupsSems = $record->groups()->pluck('semester_id');

                        $studentAlreadyInGroupInSelectedSemester = $recordGroupsSems
                            ->contains(function ($semester) use ($currentGroupSem) {
                                return $semester ==  $currentGroupSem;
                            });

                        if($studentAlreadyInGroupInSelectedSemester) {
                            Notification::make()
                                ->title('الطالب مسجل في مجموعة دراسية في هذا الفصل الدراسي')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->groups()->attach($groupId);

                        Notification::make()
                            ->title('تم اضافة المجموعة بنجاح')
                            ->success()
                            ->send();
                    })
                    ->modalHeading('إضافة مجموعة')
                    ->form([
                        Forms\Components\Select::make('Major')
                            ->options(Major::class)
                            ->label('القسم')
                            ->live(),
                        Forms\Components\Select::make('Semester')
                            ->options(fn(Forms\Get $get) => Semester::where(
                                'major',
                                $get('Major')
                            )->pluck('name', 'id'))
                            ->live()
                            ->required()
                            ->label('الفصل'),
                        Forms\Components\Select::make('groups')
                            ->label('المجموعة')
                            ->options(
                                fn(Forms\Get $get) => Group::where('semester_id', $get('Semester'))->pluck('name', 'id')
                            )


                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->label('حذف الطالب من المجموعة'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
