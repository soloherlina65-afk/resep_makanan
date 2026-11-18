<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(
            $request->user()->isAdmin() ? route('admin.dashboard') : RouteServiceProvider::HOME,
        );
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! config('services.google.client_id')
            || ! config('services.google.client_secret')
            || ! config('services.google.redirect')) {
            return redirect()->route('login')->withErrors([
                'google' => 'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, dan GOOGLE_REDIRECT_URI di file .env.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (! $user) {
            $user = new User([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Racikara',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
                'avatar' => $googleUser->getAvatar(),
            ]);
            $user->role = User::ROLE_USER;
            $user->save();
        } elseif (! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun ini sedang dinonaktifkan. Hubungi administrator.',
            ]);
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
                'avatar' => $googleUser->getAvatar() ?: $user->avatar,
            ])->save();
        }

        Auth::login($user, true);

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : RouteServiceProvider::HOME,
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
