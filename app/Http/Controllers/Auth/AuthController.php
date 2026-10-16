<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attempt = Auth::attempt([
            $loginType => $credentials['login'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'));

        if ($attempt) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'login' => 'Kombinasi kredensial (email/username dan kata sandi) tidak cocok dengan catatan kami.',
        ])->onlyInput('login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:30', 'unique:users,username'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'username.alpha_dash' => 'Username hanya boleh terdiri dari huruf, angka, tanda strip (-), dan garis bawah (_).',
            'username.unique' => 'Username ini sudah digunakan, silakan pilih username lain.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan login menggunakan akun Anda.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal harus terdiri dari 8 karakter.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Failed sending verification email: ' . $e->getMessage());
        }

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Tautan verifikasi email telah dikirimkan ke ' . $user->email . '. Silakan verifikasi email Anda untuk melanjutkan transaksi membership.');
    }

    public function showVerifyNotice(Request $request): View|RedirectResponse
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('membership.payment'));
        }

        return view('auth.verify-email');
    }

    public function verifyEmail(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return redirect()->intended(route('membership.payment'))
            ->with('success', 'Alamat email berhasil diverifikasi! Sekarang Anda dapat melanjutkan pembelian paket membership.');
    }

    public function resendVerificationEmail(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('membership.payment'));
        }

        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('success', 'Tautan verifikasi email baru telah berhasil dikirimkan ke ' . $request->user()->email . '!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengirim email verifikasi: server mail belum aktif.');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
