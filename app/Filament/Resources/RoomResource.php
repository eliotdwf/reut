<?php

namespace App\Filament\Resources;

use App\Enums\Permission;
use App\Enums\RoomType;
use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Closure;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom de la salle')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),
                Grid::make()
                    ->columns(3)
                    ->schema([
                        TextInput::make('number')
                            ->label('Numéro')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('BFXXXX, PGXXXX, ...')
                            ->helperText('Numéro administratif de la salle'),
                        TextInput::make('capacity')
                            ->label('Capacité')
                            ->numeric()
                            ->nullable()
                            ->helperText('Nombre maximum de personnes dans la salle')
                            ->minValue(1)
                            ->maxValue(100),
                        ColorPicker::make('color')
                            ->label('Couleur')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->hexColor()
                            ->placeholder('#FF5733')
                            ->helperText('Code couleur hexadécimal pour l\'affichage dans le calendrier')
                    ]),
                Select::make('room_type')
                    ->label('Type de salle')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(RoomType::casesAsKeyValueArray(true))
                    ->placeholder('Sélectionnez un type de salle')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->nullable()
                    ->placeholder('La salle ... se situe à ..., elle est équipée de ...')
                    ->columnSpanFull(),
                Textarea::make('access_conditions')
                    ->label('Conditions d\'accès')
                    ->maxLength(500)
                    ->nullable()
                    ->columnSpanFull()
                    ->placeholder('L\'accès à la salle n\'est possible que si ...'),
                Repeater::make('accessibleTimes')
                    ->relationship('accessibleTimes')
                    ->schema([
                        Select::make('weekday')
                            ->disabled()
                            ->dehydrated()
                            ->label('Jour de la semaine')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->options([
                                'Monday'    => 'Monday',
                                'Tuesday'   => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday'  => 'Thursday',
                                'Friday'    => 'Friday',
                                'Saturday'  => 'Saturday',
                                'Sunday'    => 'Sunday',
                            ]),
                        Placeholder::make('')
                            ->content('Laisser vide les deux champs (--:--) si la salle n\'est pas accessible ce jour-là')
                            ->columnSpanFull(),
                        TimePicker::make('opens_at')
                            ->label('Ouverture')
                            ->nullable()
                            ->columnSpan(1)
                            ->seconds(false),
                        TimePicker::make('closes_at')
                            ->label('Fermeture')
                            ->rules([
                                fn($state, Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($state, $get) {
                                    if($state <= $get('opens_at')) {
                                        $fail('La fermeture doit être après l\'ouverture.');
                                    }
                                }
                            ])
                            ->nullable()
                            ->columnSpan(1)
                            ->seconds(false),
                    ])
                    ->addable(false)
                    ->columnSpanFull()
                    ->deletable(false)
                    ->reorderable(false)
                    ->columns(4)
                    ->visible(fn (string $context): bool => $context === 'create') // Only show on creation, use RelationManager for editing
                    ->default(function () {
                        // Used only on creation to prefill 7 weekdays

                        return collect([
                            ['weekday' => 'Monday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Tuesday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Wednesday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Thursday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Friday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Saturday', 'opens_at' => '08:00', 'closes_at' => '20:00'],
                            ['weekday' => 'Sunday', 'opens_at' => null, 'closes_at' => null],
                        ]);
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('Couleur'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de la salle')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room_type')
                    ->label('Type de salle')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacité')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('room_type')
                    ->label('Type de salle')
                    ->multiple()
                    ->options(RoomType::casesAsKeyValueArray()),
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
            RelationManagers\AccessibleTimeRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }

    /**
     * @return string|null
     */
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermission(Permission::MANAGE_ROOMS->value);
        //return auth()->user()->hasPermission(Permission::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS->value);
    }
}
