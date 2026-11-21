<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('login')->with('error', __('Foydalanuvchi topilmadi.'));
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // If currently authenticated as this user on this browser
        if (Auth::check() && Auth::id() == $user->id) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        // If clicked on phone or another browser where user is a guest
        return redirect()->route('login')->with('status', __('Emailingiz muvaffaqiyatli tasdiqlandi! Endi hisobingizga kirishingiz mumkin.'));
    }
}
