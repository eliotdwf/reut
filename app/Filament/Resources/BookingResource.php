<?php

namespace App\Filament\Resources;

use App\Constants;
use App\Enums\Permission;
use App\Enums\RoomType;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Salle')
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
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->visible(fn (Booking $record) => $record->canUserUpdateDelete(auth()->user())),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn (Booking $record) => $record->canUserUpdateDelete(auth()->user())),
                ])
                ->tooltip('Actions')
            ])
            ->bulkActions([
                //
            ])
            ->recordAction('view');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ...self::getInfoList(),
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

    public static function getInfoList(): array
    {
        return [
            TextEntry::make('title')
                ->columnSpanFull()
                ->label('Intitulé de la réservation')
                ->inlineLabel(),
            TextEntry::make('starts_at')
                ->columnSpanFull()
                ->label('Début de la réservation')
                ->dateTime('d/m/Y H:i')
                ->inlineLabel(),
            TextEntry::make('ends_at')
                ->columnSpanFull()
                ->label('Début de la réservation')
                ->dateTime('d/m/Y H:i')
                ->inlineLabel(),
            TextEntry::make('asso.shortname')
                ->columnSpanFull()
                ->label('Association')
                ->inlineLabel()
                ->visible(fn (Booking $record) => !$record->booking_perso),
            TextEntry::make('creator.email')
                ->columnSpanFull()
                ->label('Créateur de la réservation')
                ->inlineLabel()
                ->visible(fn (Booking $record) => $record->isUserAuthor(auth()->user())),
            \Filament\Infolists\Components\Section::make('')
                ->schema([
                    TextEntry::make('booking_perso')
                        ->columnSpanFull()
                        ->formatStateUsing(fn($state) => $state ? 'Réservation personnelle (pas dans le cadre d\'une association)': 'Réservation pour une association')
                        ->label('')
                ])
                ->visible(fn (Booking $record) => $record->booking_perso),
            \Filament\Infolists\Components\Section::make('')
                ->schema([
                    TextEntry::make('open_to_others')
                        ->columnSpanFull()
                        ->label('')
                        ->icon(fn(Booking $record) => $record->open_to_others ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                        ->iconColor(fn(Booking $record) => $record->open_to_others ? 'success' : 'danger')
                        ->formatStateUsing(fn(Booking $record) => $record->open_to_others ? 'Réservation publique, ouverte aux autres' : 'Réservation privée, fermée aux autres'),
                ]),
            \Filament\Infolists\Components\Section::make(fn ($record) => 'À propos de la salle : ' . $record->room->name . ' ('. $record->room->number.')')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextEntry::make('room.name')
                        ->inlineLabel()
                        ->label('Salle'),
                    TextEntry::make('room.number')
                        ->inlineLabel()
                        ->label('Numéro de la salle'),
                    TextEntry::make('room.capacity')
                        ->inlineLabel()
                        ->label('Capacité maximale')
                        ->formatStateUsing(fn($state) => $state ? $state . ' personnes' : null)
                        ->placeholder('Non renseignée'),
                    TextEntry::make('room.description')
                        ->label('Description de la salle')
                        ->inlineLabel()
                        ->placeholder('Aucune description renseignée'),
                    TextEntry::make('room.access_conditions')
                        ->inlineLabel()
                        ->placeholder('Aucune condition d\'accès renseignée')
                        ->label('Conditions d\'accès'),
                ])
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
                ->columnSpanFull()
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
                                fn($state, Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($state, $get) {
                                    $user = auth()->user();
                                    if ($state === true && !$user->canMakePersoBooking($get('starts_at'), $get('ends_at'))) {
                                        // If the user cannot make personal bookings because he has reached the limit, we return a custom validation rule
                                        $fail('Vous avez atteint la limite de réservations personnelles pour cette semaine (3h max). Vous pouvez uniquement faire une réservation pour vos associations.');
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
                    return $query->get()->mapWithKeys(fn ($room) => [
                        $room->id => "[{$room->number}] {$room->name}"
                    ]);

                })
                ->required()
                ->afterStateUpdated(fn(Set $set) => $set('conditionCheck', false))
                ->helperText('Certaines salles peuvent ne pas apparaître en fonction de votre rôle sur le Portail des assos et de si la réservation est une réservation personnelle ou non.')
                // ->searchable() // seem to break the options when reactive functionality
                ->columnSpanFull(),
            Grid::make()
                ->schema([
                    DateTimePicker::make('starts_at')
                        ->label('Début')
                        ->reactive()
                        ->minDate(now())
                        ->rules([
                            fn($state, Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($state, $get) {
                                // Two validation rules:
                                // 1. The room must be accessible on the selected day of the week
                                // 2. The booking start time must be within the room's accessible times for that day

                                $room = Room::find($get('room_id'));
                                $weekday = Carbon::parse($state)->format('l');

                                $isWeekdayValid = $room->isRoomOpenByWeekday($weekday);
                                if(!$isWeekdayValid) {
                                    $frenchWeekday = Constants::WEEKDAYS_FR[$weekday] ?? $weekday;
                                    $fail('La salle n\'est pas accessible le ' . strtolower($frenchWeekday) . '.');
                                }
                                else {
                                    $startsTime = Carbon::parse($state)->format('H:i');
                                    $startTimeValidationError = $room->checkBookingTimeValid($weekday, $startsTime);
                                    if($startTimeValidationError) {
                                        $fail($startTimeValidationError);
                                    }
                                }
                            }
                        ])
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Fin')
                        ->reactive()
                        ->after('starts_at') // Ensure end time is after start time
                        ->validationMessages([
                            'after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
                        ])
                        ->beforeOrEqual(function() {
                            $currentUser = auth()->user();
                            if( $currentUser->hasPermission(Permission::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE->value)) {
                                return null; // No limit for users with permission to book over two weeks in advance
                            }
                            return now()->addWeeks(2); // Limit to two weeks in advance for other users
                        })
                        ->rules([
                            fn($state, Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($state, $get) {
                                // Three validation rules:
                                // 1. The weekday of the booking's ending time must be the same as the start of the booking
                                // 2. The booking end time must be within the room's accessible times for that day
                                // 3. Check that the booking doesn't overlap with another booking for this room

                                // compare the date of the start and end times
                                $startsAtDate = Carbon::parse($get('starts_at'))->format('Y-m-d');
                                $endsAtDate = Carbon::parse($state)->format('Y-m-d');
                                if ($startsAtDate !== $endsAtDate) {
                                    $fail('L\'heure de fin doit être le même jour que l\'heure de début.');
                                }

                                $room = Room::find($get('room_id'));
                                $weekday = Carbon::parse($state)->format('l');
                                $endsTime = Carbon::parse($state)->format('H:i');
                                $endTimeValidationError = $room->checkBookingTimeValid($weekday, $endsTime);
                                if ($endTimeValidationError) {
                                    $fail($endTimeValidationError);
                                }

                                $isRoomAlreadyBooked = $room->isAlreadyBooked(Carbon::parse($get('starts_at')),Carbon::parse($state));
                                if ($isRoomAlreadyBooked) {
                                    $fail('La salle est déjà réservée pour cette période.');
                                }
                            }
                        ])
                        ->required(),
                    Placeholder::make('Horaires de la salle')
                        ->content(function(Get $get) {
                            $room = Room::find($get('room_id'));
                            $weekday = Carbon::parse($get('starts_at'))->format('l');
                            if(!$room) {
                                return 'Veuillez sélectionner une salle pour afficher les horaires.';
                            }
                            $accessibleTimes = $room->accessibleTimes->firstWhere('weekday', $weekday);
                            $frenchWeekday = strtolower(Constants::WEEKDAYS_FR[$weekday] ?? $weekday);
                            if(!$accessibleTimes->opens_at || !$accessibleTimes->closes_at) {
                                return "La salle n'est pas accessible le " . $frenchWeekday. ".";
                            }
                            return "La salle est accessible de " . $accessibleTimes->opens_at . " à " . $accessibleTimes->closes_at . " le " . $frenchWeekday . ".";
                        })
                        ->visible(fn($get) => $get('room_id') && $get('starts_at'))
                        ->columnSpanFull(),
                    Placeholder::make('Attention')
                        ->content(new HtmlString('<i>Vous ne pouvez pas réserver une salle plus de deux semaines à l\'avance.</i>'))
                        ->columnSpanFull()
                        ->visible(fn() => !auth()->user()->hasPermission(Permission::CREATE_BOOKINGS_OVER_TWO_WEEKS_BEFORE->value))
                ]),
            Section::make()
                ->schema([
                    Placeholder::make("roomAccessConditions")
                        ->label('Conditions d\'accès à la salle')
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
                        ->dehydrated(false)
                        ->columnSpanFull(),
                ])
                ->visible(fn(Get $get): bool => ($get('room_id') && Room::find($get('room_id'))->access_conditions != null)),
        ];
    }

    public static function canViewAny(): bool
      {
          return auth()->user()->hasPermission(Permission::MANAGE_ROOMS->value);
          //return auth()->user()->hasPermissions([Permission::UPDATE_DELETE_BOOKINGS_MDE_ROOMS->value, Permission::UPDATE_DELETE_BOOKINGS_MUSIC_DANCE_ROOMS->value], false);
      }
}
