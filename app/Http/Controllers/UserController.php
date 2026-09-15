<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        // Hanya Admin yang boleh mengakses halaman ini
        if (!Gate::allows('is-admin')) {
            abort(403, 'Akses Ditolak. Khusus Admin.');
        }

        // Mengambil semua user, diurutkan dari yang belum disetujui, lalu terbaru
        $users = User::orderBy('is_approved', 'asc')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function edit($id)
    {
        // Hanya Admin Utama yang bisa mengakses
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // =========================================================
        // SKENARIO 1: Update dari halaman Edit (Nama, Email, Password)
        // =========================================================
        if ($request->has('name') && $request->has('email')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8', // Opsional, hanya divalidasi jika diisi
            ]);

            $user->name = $request->name;
            $user->email = $request->email;

            // Jika form password diisi, enkripsi dan simpan password baru
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
            return redirect()->route('users.index')->with('success', 'Profil dan Sandi petugas ' . $user->name . ' berhasil diperbarui.');
        }

        // =========================================================
        // SKENARIO 2: Update dari halaman Index (Role & Status Approval)
        // =========================================================
        if ($request->has('role') && $request->has('is_approved')) {

            // Cegah modifikasi role/akses pada akun Admin Utama (Super Admin)
            if ($user->id === 1 || $user->email === 'admin@admin.com') {
                return redirect()->back()->with('error', 'Akses Ditolak! Hak akses Admin Utama bersifat permanen dan tidak dapat diubah.');
            }

            $user->role = $request->role;
            $user->is_approved = $request->is_approved;
            $user->save();

            return redirect()->back()->with('success', 'Hak akses petugas berhasil diperbarui.');
        }

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        if (!Gate::allows('is-admin')) {
            abort(403, 'Akses Ditolak.');
        }

        $user = User::findOrFail($id);

        // Mencegah admin menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun pengguna berhasil dihapus permanen!');
    }
}