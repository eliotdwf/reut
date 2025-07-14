<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        //return redirect()->route('auth.logout');
        info("User logged out successfully, redirecting to ". config('services.oauth.logout_url'));

        return redirect(config('services.oauth.logout_url'));
    }
}
