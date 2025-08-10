<?php

namespace App\Filament\Resources\AssoResource\Pages;

use App\Filament\Resources\AssoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsso extends EditRecord
{
    protected static string $resource = AssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
