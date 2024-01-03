<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IzinController extends Controller
{
    public function listIzin()
    {
        $izin = Izin::all();

        return response()->json([
            'status' => 'success',
            'message' => 'List Izin',
            'data' => $izin
        ]);
    }

    public function pengajuanIzin(Request $request)
    {
        try {
            DB::beginTransaction();

            $izin = Izin::create([
                'user_id' => auth()->user()->id ?? $request->user_id,
                'tanggal_izin' => $request->tanggal_izin,
                'alasan_izin' => $request->alasan_izin,
                'status_pengajuan' => 0,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan izin Berhasil',
                'data' => $izin
            ]);
        } catch (Error $error) {
            DB::rollback();
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan izin gagal',
            ], 400);
        }
    }
}
