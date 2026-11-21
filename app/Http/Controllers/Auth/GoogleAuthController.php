<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google OAuth consent screen.
     */
    public function redirect(Request $request)
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect') ?: url('/auth/google/callback');

        if (empty($clientId)) {
            return redirect()->route('login')->with('error', __('Google orqali kirish hozirda sozlanmagan. Iltimos, email va parol orqali kiring yoki administratorga murojaat qiling.'));
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $params = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    }

    /**
     * Handle the callback from Google OAuth.
     */
    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('login')->with('error', __('Google orqali autentifikatsiya bekor qilindi.'));
        }

        $code = $request->query('code');
        $state = $request->query('state');
        $savedState = $request->session()->pull('google_oauth_state');

        if (empty($code) || empty($state) || $state !== $savedState) {
            return redirect()->route('login')->with('error', __('Xavfsizlik tekshiruvi amalga oshmadi. Iltimos qaytadan urinib ko\'ring.'));
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect') ?: url('/auth/google/callback');

        try {
            $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirectUri,
                'grant_type' => 'authorization_code',
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Google OAuth token error', ['response' => $tokenResponse->body()]);
                return redirect()->route('login')->with('error', __('Google bilan bog\'lanishda xatolik yuz berdi.'));
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                return redirect()->route('login')->with('error', __('Google tokeni olinmadi.'));
            }

            $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

            if (!$userResponse->successful()) {
                Log::error('Google OAuth userinfo error', ['response' => $userResponse->body()]);
                return redirect()->route('login')->with('error', __('Google ma\'lumotlarini yuklab bo\'lmadi.'));
            }

            $googleUser = $userResponse->json();
            $googleId = $googleUser['sub'] ?? null;
            $email = $googleUser['email'] ?? null;
            $name = $googleUser['name'] ?? explode('@', $email)[0];
            $avatar = $googleUser['picture'] ?? null;

            if (!$email) {
                return redirect()->route('login')->with('error', __('Google hisobingizda email topilmadi.'));
            }

            // Check if user exists by google_id
            $user = User::where('google_id', $googleId)->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $email)->first();

                if ($user) {
                    $user->update([
                        'google_id' => $googleId,
                        'avatar' => $avatar ?? $user->avatar,
                        'email_verified_at' => $user->email_verified_at ?: now(),
                    ]);
                } else {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'google_id' => $googleId,
                        'avatar' => $avatar,
                        'password' => bcrypt(Str::random(32)),
                        'role' => 'client',
                        'email_verified_at' => now(),
                    ]);
                }
            } else {
                if ($avatar && $user->avatar !== $avatar) {
                    $user->update(['avatar' => $avatar]);
                }
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            if ($user->role === 'admin' || $user->email === 'nursherzil@gmail.com') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));

        } catch (\Throwable $e) {
            Log::error('Google OAuth exception', ['message' => $e->getMessage()]);
            return redirect()->route('login')->with('error', __('Tizimda xatolik: ') . $e->getMessage());
        }
    }
}
