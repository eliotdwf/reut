<?php

namespace App\Filament\Resources\AssoResource\Pages;

use App\Filament\Resources\AssoResource;
use App\Services\SyncAssosService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListAssos extends ListRecords
{
    protected static string $resource = AssoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync_assos')
                ->label('Synchroniser les assos')
                ->action(function() {
                    $syncResult = app(SyncAssosService::class)->sync();
                    if ($syncResult['success']) {
                        $assosAdded = $syncResult['details']['added'];
                        $assosBuried = $syncResult['details']['buried'];
                        $assosRevived = $syncResult['details']['revived'];
                        $successMessage = "Synchro réussie : $assosAdded assos ajoutées, $assosBuried assos désactivées, $assosRevived assos réactivées.";
                        Notification::make('Synchro réussie !')
                            ->success()
                            ->body($successMessage)
                            ->send();
                    } else {
                        Notification::make('Erreur de synchro')
                            ->danger()
                            ->body('Erreur de synchro'.$syncResult['message'])
                            ->send();
                    }
                })
        ];
    }
}
