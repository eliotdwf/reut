<x-filament::page>
    {{ $this->form }}
    @livewire(\App\Filament\Widgets\CalendarWidget::class, [
    'selectedRoomIDs' => $selectedRoomIDs,
    ], key(implode('-', $selectedRoomIDs)))
</x-filament::page>
