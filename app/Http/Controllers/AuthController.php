<?php

namespace App\Http\Controllers;

use App\Enums\Permission;
use App\Providers\RouteServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class AuthController extends Controller
{
    /**
     * The OAuth2 provider instance.
     *
     * @var GenericProvider
     */
    public GenericProvider $provider;
    private string $currentAssociationsUrl;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Initialize the OAuth2 provider with configuration values from environment variables
        $this->provider = new GenericProvider([
            'clientId' => config("services.oauth.client_id"),
            'clientSecret' => config("services.oauth.client_secret"),
            'redirectUri' => config("services.oauth.redirect_uri"),
            'urlAuthorize' => config("services.oauth.authorize_url"),
            'urlAccessToken' => config("services.oauth.access_token_url"),
            'urlResourceOwnerDetails' => config("services.oauth.resource.owner_details_url"),
            'scopes' => config("services.oauth.scopes"),
        ]);

        $this->currentAssociationsUrl = config("services.oauth.resource.current_associations_url");
    }

    /**
     * Handle user login via OAuth2.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {

        // Save the previous URL as the intended url to redirect after login
        if(!$request->session()->has('url.intended')) {
            Log::debug("Setting intended URL to previous URL: " . url()->previous());
            $request->session()->put('url.intended', url()->previous());
        } else {
            Log::debug("Intended URL already set: " . $request->session()->get('url.intended'));
        }

        // Generate a random state parameter
        $state = bin2hex(random_bytes(16));
        $request->session()->put('oauth2state', $state);
        // Redirect the user to the OAuth2 authorization URL
        $authorizationUrl = $this->provider->getAuthorizationUrl([
            'state' => $state
        ]);
        Log::debug("Redirecting to authorization URL: $authorizationUrl");

        return redirect($authorizationUrl);
    }

    /**
     * Handle the OAuth2 callback.
     *
     * @param Request $request The HTTP request object.
     * @param UserController $userController The user controller instance.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request, UserController $userController)
    {
        Log::debug("Callback received with request: " . $request->fullUrl());
        // Retrieve the state parameter from the session
        $storedState = $request->session()->pull('oauth2state');

        // Check if the state parameter is present and valid
        if (!$request->has('state') || $request->get('state') !== $storedState) {
            abort(400, 'Invalid state: ' . $request->get('state') . ' VS ' . $storedState);
        }

        // Check if the authorization code is present
        if (!$request->has('code')) {
            abort(400, 'No authorization code');
        }

        try {
            // Exchange the authorization code for an access token
            $accessToken = $this->provider->getAccessToken('authorization_code', [
                'code' => $request->get('code')
            ]);

            $resourceOwner = $this->provider->getResourceOwner($accessToken);
            $userDetails = $resourceOwner->toArray();

            $resourceCurrentAssociations = Http::withToken($accessToken->getToken())
                ->get($this->currentAssociationsUrl);

            $currentAssociations = $resourceCurrentAssociations->json();
            info("Current associations: " . json_encode($currentAssociations));

            // Check if user account has been deleted
            if ($userDetails['deleted_at'] != null) {
                abort(401, 'Account deleted');
            }

            // Check if user account is provided from cas
            if ($userDetails['provider'] != 'cas') {
                abort(401, 'Provider unauthorized');
            }

            // Create or update the user in the database
            $user = $userController->createOrUpdateUser($userDetails, $currentAssociations);

            // Store the user in the session
            Auth::login($user);

            Log::debug("User logged in: " . auth()->user()->id);

            // Update the user's permissions stored in the session
            $request->session()->put('user_permissions', $user->getPermissions());
            info("User permissions stored in session: ", $request->session()->get('user_permissions'));

            // Redirect to the intended url or home if not set
            info("Redirecting to intended URL: " . $request->session()->get('url.intended'));
            return redirect()->intended(RouteServiceProvider::HOME)->with('theme-dark', $user->dark_theme);
            //return redirect($request->session()->pull('url.intended'))->with('theme-dark', $user->dark_theme);

        } catch (IdentityProviderException $e) {
            abort(400, 'Authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request The HTTP request object.
     */
    public function logout(Request $request)
    {
        info("Logging out user: " . auth()->user()->id);
        // Clear the user's session data
        $request->session()->forget('user');

        Filament::auth()->logout();
        info("Filament user logged out successfully");

        // Log the user out
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();
        info("User logged out successfully, redirecting to ". config('services.oauth.logout_url'));

        return redirect(config('services.oauth.logout_url'));
    }
}
