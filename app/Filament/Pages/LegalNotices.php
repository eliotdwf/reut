<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LegalNotices extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Mentions légales';

    protected static ?string $slug = 'mentions-legales';

    protected static string $view = 'filament.pages.legal-notices';

    protected static bool $shouldRegisterNavigation = false;
}
