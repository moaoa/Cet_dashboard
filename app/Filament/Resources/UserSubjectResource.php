<?php

namespace App\Filament\Resources;

use App\Enums\Major;
use App\Filament\Exports\UserSubjectExporter;
use App\Filament\Resources\UserSubjectResource\Pages;
use App\Filament\Resources\UserSubjectResource\RelationManagers;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
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
                    ->label('الطالب')
                    ->required()
                    ->searchable()
                    ->getSearchResultsUsing(fn(string $search): array => User::where('ref_number', 'like', "%{$search}%")->limit(50)->pluck('name', 'id')->toArray())
                    ->getOptionLabelUsing(fn($value): ?string => User::find($value)?->name)
                    ->live(),

                Forms\Components\Select::make('Major')
                    ->options(Major::class)
                    ->label('التخصص')
                    ->live(),
                Forms\Components\Select::make('Semster')
                    ->options(fn(Forms\Get $get) => Semester::where(
                        'major',
                        $get('Major')
                    )->pluck('name', 'id'))
                    ->label('الفصل الدراسي')
                    ->live(),
                Forms\Components\Select::make('subject_id')
                    ->options(fn(Forms\Get $get) => Subject::where('semester_id', $get('Semster'))->pluck('name', 'id'))
                    // ->relationship('subject', 'name')
                    ->label('المادة')
                    ->live()
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
                Tables\Columns\TextColumn::make('user.ref_number')
                    ->sortable()
                    ->searchable()
                    ->label('رقم القيد'),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('الطالب'), // Translated label for "User"
                Tables\Columns\TextColumn::make('subject.name')
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
                        Forms\Components\Select::make('subject_id')
                            ->options(
                                fn(Forms\Get $get) => Subject::where(
                                    'semester_id',
                                    $get('semester_id')
                                )->pluck('name', 'id')
                            )
                            ->label('المادة الدراسية')
                            ->live(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['subject_id']) && $data['subject_id'] !== null) {
                            $query->where('subject_id', $data['subject_id']);
                        }
                        return $query;
                    })
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(UserSubjectExporter::class),
                // Tables\Actions\AttachAction::make()
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
