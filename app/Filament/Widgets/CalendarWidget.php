<?php

namespace App\Filament\Widgets;

use App\Enums\Permission;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Closure;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    use InteractsWithForms;

    //protected static string $view = 'filament.widgets.calendar-widget';

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        return Booking::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Booking $booking) => [
                    'id' => $booking->id,
                    'title' => $booking->title,
                    'start' => $booking->starts_at,
                    'end' => $booking->ends_at,
                    'url' => BookingResource::getUrl(name: 'edit', parameters: ['record' => $booking]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }

    public string|null|Model $model = Booking::class;

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'dayGridWeek,dayGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    /**
     * Get the actions to be displayed in the header of the calendar widget.
     * @return array
     */
    protected function headerActions(): array
    {
        return [
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
