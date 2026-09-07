<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterOpsi;

class MasterOpsiController extends Controller
{
    public function storeAjax(Request $request)
    {
        $request->validate(['kategori' => 'required', 'nilai' => 'required']);
        $opsi = MasterOpsi::create(['kategori' => $request->kategori, 'nilai' => $request->nilai]);
        return response()->json(['success' => true, 'data' => $opsi]);
    }

    public function destroyAjax($id)
    {
        MasterOpsi::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}