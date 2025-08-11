<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PrivacyPolicies extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Politique de confidentialité';

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'politique-confidentialite';

    protected static string $view = 'filament.pages.privacy-policies';
}
