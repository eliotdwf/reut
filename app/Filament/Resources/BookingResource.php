<?php

namespace App\Filament\Resources;

use App\Enums\Permission;
use App\Enums\RoomType;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(25)
            ->columns([
                Tables\Columns\TextColumn::make('creator.email')
                    ->label('Créateur')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Intitulé')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('open_to_others')
                    ->label('Ouverte aux autres')
                    ->boolean()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Début')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fin')
                    ->dateTime()
                    ->sortable(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('title'),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }

    /**
     * Get the form schema for creating or editing a booking.
     * @return array
     */
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('user_id')
                ->label('Créateur de la réservation')
                ->disabled()
                ->formatStateUsing(function ($state) {
                    return User::find($state)->email ?? 'Impossible de récupérer le créateur de la réservation';
                })
                ->visibleOn('edit'),
            TextInput::make('title')
                ->label('Intitulé')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            Grid::make()
                ->schema([
                    Checkbox::make('booking_perso')
                        ->label('Réservation personnelle')
                        ->default(false)
                        ->rules(
                            [
                                fn($state): Closure => function (string $attribute, $value, Closure $fail) use ($state) {
                                    $user = auth()->user();
                                    if ($state === true && !$user->canMakePersoBooking()) {
                                        // If the user cannot make personal bookings because he has reached the limit, we return a custom validation rule
                                        $fail('Vous avez atteint la limite de réservations personnelles pour cette semaine (3h max). Vous pouvez uniquement faire une réservation pour votre association.');
                                    }
                                },
                            ])
                        ->reactive()
                        ->helperText('Cochez si la réservation est pour vous personnellement, sinon laissez décoché pour une association.')
                        ->afterStateUpdated(fn(Set $set) => $set('room_id', null)),
                    Checkbox::make('open_to_others')
                        ->label('Ouverte aux autres utilisateurs')
                        ->default(false)
                        ->helperText('Cochez si vous souhaitez que d\'autres utilisateurs puissent utiliser la salle durant votre réservation.'),
                ]),
            Select::make('asso_id')
                ->label('Association')
                ->relationship('asso', 'name')
                ->options(function () {
                    $user = auth()->user();
                    return $user->assos()->pluck('shortname', 'id');
                })
                ->searchable()
                ->required(fn(Get $get) => !$get('booking_perso'))
                ->placeholder('Sélectionnez une association')
                ->visible(fn(Get $get) => !$get('booking_perso'))
                ->columnSpanFull(),
            Select::make('room_id')
                ->reactive()
                ->label('Salle')
                ->relationship('room', 'name')
                ->options(function (Get $get) {
                    $user = auth()->user();
                    $query = Room::query();

                    if ($get('booking_perso')) {
                        // retrieve all rooms that allow personal bookings
                        $query->whereIn('room_type', RoomType::bookingPersoAllowedValues());
                    } else {    // Booking for an association
                        if (!$user->hasPermission(Permission::CREATE_BOOKINGS_MUSIC_DANCE_ROOMS_ASSO->value)) {
                            $query->whereNotIn('room_type', [RoomType::MUSIC->value, RoomType::DANCE->value]);
                        }
                    }
                    return $query->get()->pluck('name', 'id');

                })
                ->required()
                ->afterStateUpdated(fn(Set $set) => $set('conditionCheck', false))
                ->helperText('Certaines salles peuvent ne pas apparaître en fonction de votre rôle sur le portail des assos et de si la réservation est une réservation personnelle ou non.')
                // ->searchable() // seem to break the options when reactive functionality
                ->columnSpanFull(),
            Grid::make()
                ->schema([
                    DateTimePicker::make('starts_at')
                        ->label('Début')
                        ->default(now()) // Set default to current time
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Fin')
                        ->default(time() + 60 * 60) // Set default to now + 1 hour
                        ->required(),
                ]),
            Placeholder::make("roomAccessConditions")
                ->label('Conditions d\'accès à la salle')
                ->visible(fn(Get $get): bool => ($get('room_id') && Room::find($get('room_id'))->access_conditions != null))
                ->content(function (Get $get) {
                    $roomId = $get('room_id');
                    if ($roomId) {
                        $room = Room::find($roomId);
                        if ($room) {
                            if (!$room->access_conditions) {
                                return 'Aucune condition d\'accès définie pour cette salle.';
                            }
                            else {
                                return $room->access_conditions;
                            }
                        }
                        else {
                            return 'Impossible de trouver la salle avec l\'ID ' . $roomId;
                        }
                    }
                    return 'Veuillez séléctionner une salle dans la liste';
                })
                ->columnSpanFull(),
            Checkbox::make('conditionCheck')
                ->label('J\'ai lu et j\'accepte les conditions d\'accès à la salle')
                ->required(fn(Get $get): bool => ($get('room_id') && Room::find($get('room_id'))->access_conditions != null))
                ->visible(fn(Get $get): bool => ($get('room_id') && Room::find($get('room_id'))->access_conditions != null))
                ->dehydrated(false)
                ->columnSpanFull(),
        ];
    }

    public static function canViewAny(): bool
      {
          return auth()->user()->hasPermission(Permission::MANAGE_ROOMS->value);
          //return auth()->user()->hasPermissions([Permission::UPDATE_DELETE_BOOKINGS_MDE_ROOMS->value, Permission::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS->value], false);
      }
}
