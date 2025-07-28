<?php

namespace App\Filament\Widgets;

use App\Enums\Permission;
use App\Enums\RoomType;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    use InteractsWithForms;

    public array $selectedRoomIDs = [];
    public string|null $filterBookingOpenToOthers = null;

    public string|null|Model $model = Booking::class;


    //protected static string $view = 'filament.widgets.calendar-widget';

    public function config(): array
    {
        return [
            'firstDay' => 1,                // Monday as the first day of the week
            'allDaySlot' => false,          // disables the "All-day" row
            'editable' => false,            // disables dragging and resizing
            'headerToolbar' => [
                'left' => 'timeGridWeek,timeGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
            'initialView' => 'timeGridWeek', // shows time slots
            'slotMinTime' => '06:00:00',     // optional: start time
            'slotMaxTime' => '23:59:59',     // optional: end time
            'slotDuration' => '00:30:00',    // optional: time slot interval
            'scrollTime' => '8:00:00',
        ];
    }


    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        $query = Booking::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->whereIn('room_id', $this->selectedRoomIDs);

        if ($this->filterBookingOpenToOthers != null) {
            $bookingOpenToOtherBool = match ($this->filterBookingOpenToOthers){
                'yes' => true,
                'no' => false,
                default => null,
            };
            if($bookingOpenToOtherBool === true || $bookingOpenToOtherBool === false) { // specify the condition only if it is true or false
                $query->where('open_to_others', $bookingOpenToOtherBool);
            }

        }
        return $query
            ->get()
            ->map(
                function (Booking $booking) {
                    $room = $booking->room->name;
                    return [
                        'id' => $booking->id,
                        'title' => "$booking->title - $room",
                        'start' => $booking->starts_at,
                        'end' => $booking->ends_at,
                        //'url' => BookingResource::getUrl(name: 'view', parameters: ['record' => $booking]),
                        'shouldOpenUrlInNewTab' => true,
                        'color' => $booking->room->color ?: '#FDCCE0', // Default color if room is not set
                    ];
                }
            )
            ->all();
    }

    /**
     * Add a JS tooltip to display the booking title when it is hovered.
     * @return string
     */
    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");

            const calendar = view.calendar;

            // Apply different view and toolbar for smaller screens
            if (window.innerWidth < 768) {
                calendar.changeView('timeGridDay');
                calendar.setOption('headerToolbar', {
                    left: 'today',
                    center: '',
                    right: 'prev,next'
                });
            }
        }
    JS;
    }


    public function viewAction(): Action
    {
        return ViewAction::make()
            ->infolist([
                TextEntry::make('title')
                    ->label('Intitulé de la réservation')
                    ->inlineLabel(),
                TextEntry::make('starts_at')
                    ->label('Début de la réservation')
                    ->dateTime('d/m/Y H:i')
                    ->inlineLabel(),
                TextEntry::make('ends_at')
                    ->label('Début de la réservation')
                    ->dateTime('d/m/Y H:i')
                    ->inlineLabel(),
                TextEntry::make('asso.shortname')
                    ->label('Association')
                    ->inlineLabel()
                    ->visible(fn (Booking $record) => !$record->booking_perso),
                TextEntry::make('creator.email')
                    ->label('Créateur de la réservation')
                    ->inlineLabel()
                    ->visible(fn (Booking $record) => $record->canUserUpdateDelete(auth()->user())),
                Section::make('')
                    ->schema([
                        TextEntry::make('booking_perso')
                            ->formatStateUsing(fn($state) => $state ? 'Réservation personnelle (pas dans le cadre d\'une association)': 'Réservation pour une association')
                            ->label('')
                    ])
                    ->visible(fn (Booking $record) => $record->booking_perso),
                Section::make('')
                    ->schema([
                        TextEntry::make('open_to_others')
                            ->label('')
                            ->icon(fn(Booking $record) => $record->open_to_others ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->iconColor(fn(Booking $record) => $record->open_to_others ? 'success' : 'danger')
                            ->formatStateUsing(fn(Booking $record) => $record->open_to_others ? 'Réservation publique, ouverte aux autres' : 'Réservation privée, fermée aux autres'),
                    ]),
                Section::make(fn ($record) => 'À propos de la salle : ' . $record->room->name)
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        TextEntry::make('room.name')
                            ->inlineLabel()
                            ->label('Salle'),
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
            ])
            ->modalFooterActions(
                function (ViewAction $action, FullCalendarWidget $livewire) {
                    $actions = [];
                    if ($this->record->canUserUpdateDelete(auth()->user())) {
                        // If the user can edit or delete the booking, add the edit and delete actions stored in the Livewire component
                        $actions = [...$livewire->getCachedModalActions()];
                    }
                    return [
                        ...$actions,
                        $action->getModalCancelAction()
                    ];
                });
    }

    /**
     * Get the actions to be displayed in the header of the calendar widget.
     * @return array
     */
    protected function headerActions(): array
    {
        return [
            // Action to create a new booking
            CreateAction::make()
                ->model(Booking::class)
                ->mutateFormDataUsing(function (array $data): array {
                    // add the user_id to the form data before creating a booking
                    return [
                        ...$data,
                        'user_id' => auth()->user()->id
                    ];
                })
                ->form($this->getFormSchema())
                ->icon('heroicon-o-plus')
                ->createAnother(false)
                ->after(fn() => $this->refreshRecords()),
        ];
    }


    /**
     * Get the form schema for creating or editing a booking.
     * @return array
     */
    public function getFormSchema(): array
    {
        return BookingResource::getFormSchema();
    }
}
