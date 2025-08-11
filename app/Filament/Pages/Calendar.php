<?php

namespace App\Filament\Pages;

use App\Models\Room;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class Calendar extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Calendrier';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $slug = 'calendrier';

    protected static string $view = 'filament.pages.calendar';

    public string|null $filterBookingOpenToOthers = null;
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
            Section::make('Filtres')
                ->collapsible()
                ->collapsed()
                ->columns(1)
                ->schema([
                    Select::make('selectedRoomIDs')
                        ->label('Filtrer les salles à afficher')
                        ->options(Room::pluck('name', 'id'))
                        ->multiple()
                        ->reactive()
                        ->afterStateUpdated(fn ($state) => $this->selectedRoomIDs = $state),
                    Select::make('filterBookingOpenToOthers')
                        ->label('Afficher les réservations ouvertes aux autres')
                        ->options([
                            'yes' => 'Oui',
                            'no' => 'Non',
                        ])
                        ->default(null)
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            $this->filterBookingOpenToOthers = $state;
                            info("Booking open to others state updated: {$state}");
                        }),
                ]),
        ];
    }
}

