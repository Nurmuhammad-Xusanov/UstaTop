<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && str_starts_with($user->avatar, '/avatars/')) {
                @unlink(public_path($user->avatar));
            }
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $dir = public_path('avatars');
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $user->avatar = '/avatars/' . $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's theme preference.
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
        ]);

        $user = $request->user();
        if ($user) {
            $user->theme = $request->input('theme');
            $user->save();
        }

        cookie()->queue(cookie()->forever('theme', $request->input('theme')));

        if ($request->wantsJson()) {
            return response()->json(['status' => 'success', 'theme' => $request->input('theme')]);
        }

        return Redirect::back()->with('status', 'theme-updated');
    }

    /**
     * Update the user's sync and language preferences.
     */
    public function updatePreferences(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => ['required', 'in:light,dark,system'],
            'locale' => ['nullable', 'in:uz,ru,en'],
            'telegram_locale' => ['nullable', 'in:uz,ru,en'],
        ]);

        $user = $request->user();
        $user->theme = $request->input('theme');
        $user->sync_theme = $request->boolean('sync_theme');
        $user->sync_telegram_lang = $request->boolean('sync_telegram_lang');
        
        if ($request->filled('locale')) {
            $locale = $request->input('locale');
            $user->locale = $locale;
            session(['locale' => $locale]);
            cookie()->queue(cookie()->forever('locale', $locale));

            if ($user->sync_telegram_lang) {
                $user->telegram_locale = $locale;
            }
        }

        if ($request->filled('telegram_locale') && !$user->sync_telegram_lang) {
            $user->telegram_locale = $request->input('telegram_locale');
        }

        $user->save();

        cookie()->queue(cookie()->forever('theme', $user->theme));

        return Redirect::route('profile.edit')->with('status', 'preferences-updated');
    }

    /**
     * Disconnect the user's Telegram account.
     */
    public function disconnectTelegram(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            $user->telegram_id = null;
            $user->save();
        }

        return Redirect::route('profile.edit')->with('status', 'telegram-disconnected');
    }
}      