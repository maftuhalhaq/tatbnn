<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        // 1. Cek email dan password (memanggil LoginRequest)
        $request->authenticate();

        // 2. Ambil data user yang baru saja berhasil login
        $user = $request->user();

        // 3. --- LOGIKA PERSETUJUAN (APPROVAL) ---
        // Jika user BUKAN admin DAN status is_approved masih 0
        if ($user->role !== 'admin' && $user->is_approved == 0) {

            // Keluarkan (logout) pengguna secara paksa dengan aman
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Arahkan langsung ke halaman waiting approval
            return redirect()->route('approval.waiting');
        }
        // ----------------------------------------

        // 4. Jika lolos (Admin atau User yang di-approve), buat sesi baru
        $request->session()->regenerate();

        // Arahkan ke Dashboard
        return redirect()->route('dashboard');
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