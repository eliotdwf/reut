<?php

namespace App\Filament\Pages;

use App\Models\Room;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Calendar extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.calendar';

    public array $selectedRoomIDs = [];
    public array $rooms = [];

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'selectedRoomIDs' => Room::pluck('id')->toArray(), // Preselect all
        ]);
        $this->rooms = Room::pluck('name', 'id')->toArray();
        $this->selectedRoomIDs = array_keys($this->rooms);
    }

    public function getFormSchema(): array
    {
        return [
            Select::make('selectedRoomIDs')
                ->label('Filtrer les salles à afficher')
                ->options(Room::pluck('name', 'id'))
                ->multiple()
                ->reactive()
                ->afterStateUpdated(fn ($state) => $this->selectedRoomIDs = $state),
        ];
    }
}

