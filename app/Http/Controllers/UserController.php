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

    public function update(Request $request, string $id)
    {
        if (!Gate::allows('is-admin')) {
            abort(403, 'Akses Ditolak.');
        }

        // PERBAIKAN VALIDASI: Menambahkan 'penginput_data' ke daftar yang diizinkan
        $request->validate([
            'role' => 'required|in:admin,penginput_data,pengedit_ba,pengedit_rekom,read_only',
            'is_approved' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);

        // Mencegah admin menghapus hak akses dirinya sendiri
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role Admin Anda sendiri!');
        }

        $user->update([
            'role' => $request->role,
            'is_approved' => $request->is_approved,
        ]);

        return redirect()->route('users.index')->with('success', 'Status dan Role pengguna berhasil diperbarui!');
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