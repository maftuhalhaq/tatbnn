<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterAnggota;

class MasterAnggotaController extends Controller
{
    // Fungsi untuk menyimpan anggota baru via AJAX
    public function storeAjax(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:medis,hukum',
        ]);

        $anggota = MasterAnggota::create([
            'nama' => $request->nama,
            'nip_nrp_sip' => $request->nip_nrp_sip,
            'pangkat' => $request->pangkat,
            'jabatan' => $request->jabatan,
            'kategori' => $request->kategori,
        ]);

        return response()->json([
            'success' => true,
            'data' => $anggota
        ]);
    }

    // FUNGSI BARU: Untuk menghapus anggota secara permanen via AJAX
    public function destroyAjax($id)
    {
        $anggota = MasterAnggota::findOrFail($id);
        $anggota->delete();

        return response()->json(['success' => true]);
    }
}