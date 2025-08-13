<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;
    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        // Redirect to the index (list table) of this resource
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected static ?string $title = 'Nouvelle réservation';

    public function getBreadcrumb(): string
    {
        return self::$title;
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Enregistrer la réservation')
                ->action('create'),

            Action::make('cancel')
                ->label('Annuler')
                ->color('gray')
                //->action('cancel')
                ->url($this->getResource()::getUrl()), // Redirect to resource index
        ];
    }
}
