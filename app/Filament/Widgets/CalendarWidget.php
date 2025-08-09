<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
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
            'slotMinTime' => '07:00:00',     // optional: start time
            'slotMaxTime' => '22:59:59',     // optional: end time
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
            ->where(function ($q) use ($fetchInfo) {
                $q->where('starts_at', '>=', $fetchInfo['start'])
                    ->orWhere('ends_at', '<=', $fetchInfo['end']);
            })
//            ->where('starts_at', '>=', $fetchInfo['start'])
//            ->where('ends_at', '<=', $fetchInfo['end'])
            ->whereIn('room_id', $this->selectedRoomIDs);

        if ($this->filterBookingOpenToOthers != null) {
            $bookingOpenToOtherBool = match ($this->filterBookingOpenToOthers) {
                'yes' => true,
                'no' => false,
                default => null,
            };
            if ($bookingOpenToOtherBool === true || $bookingOpenToOtherBool === false) { // specify the condition only if it is true or false
                $query->where('open_to_others', $bookingOpenToOtherBool);
            }

        }
        return $query
            ->get()
            ->map(
                function (Booking $booking) {
                    $roomName = $booking->room->name;
                    $roomNumber = $booking->room->number;
                    if ($booking->booking_perso) {
                        $title = '[Perso]';
                    }
                    else {
                        $asso = $booking->asso->shortname;
                        $title = "[$asso]";
                    }
                    $title .= " [$roomName - $roomNumber] "."[$booking->title]";
                    return [
                        'id' => $booking->id,
                        'title' => $title,
                        'start' => $booking->starts_at,
                        'end' => $booking->ends_at,
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
                calendar.setOption('height', '100vh');
            }
        }
    JS;
    }


    public function viewAction(): Action
    {
        return ViewAction::make()
            ->infolist(BookingResource::getInfoList())
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
                ->fillForm(function (array $arguments): array {
                    info('Mounting create action with arguments: ', $arguments);
                    if( !isset($arguments['start']) || !isset($arguments['end'])) {
                        // If start or end is not set, use default values
                        $arguments['start'] = now()->addMinutes(5);
                        $arguments['end'] = now()->addHour();
                    }
                    return [
                        'starts_at' => Carbon::parse($arguments['start']),
                        'ends_at' => Carbon::parse($arguments['end']),
                    ];
                })
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
