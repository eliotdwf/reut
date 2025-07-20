<x-filament::page>
    {{ $this->form }}
    @livewire(\App\Filament\Widgets\CalendarWidget::class, [
    'selectedRoomIDs' => $selectedRoomIDs,
    'filterBookingOpenToOthers' => $filterBookingOpenToOthers,
    ], key(implode('-', $selectedRoomIDs).$filterBookingOpenToOthers))
</x-filament::page>
