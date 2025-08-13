<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;

class UserAccount extends Page implements HasForms, HasInfolists
{
    use InteractsWithInfolists;
    use InteractsWithForms;

    public User $user;

    public function mount(): void
    {
        // For example purposes, load the first user
        $this->user = auth()->user();
    }

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $slug = 'mon-compte';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Mon compte';

    protected static string $view = 'filament.pages.user-account';

    public function userInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Infos personnelles')
                            ->schema([
                                TextEntry::make('first_name')
                                    ->label('Prénom'),
                                TextEntry::make('last_name')
                                    ->label('Nom'),
                                TextEntry::make('email'),
                                TextEntry::make('created_at')
                                    ->label('Créé le')
                                    ->dateTime('d/m/Y H:i'),
                                TextEntry::make('updated_at')
                                    ->label('Mis à jour le')
                                    ->since(),
                            ])
                            ->columns(3),
                        Tab::make('Permissions')
                            ->schema([
                                TextEntry::make('permissions.key')
                                    ->label('')
                                    ->listWithLineBreaks()
                                    ->badge()
                            ])
                            ->visible(fn($record) => $record->permissions->isNotEmpty()),
                    ])
            ]);
    }
}
