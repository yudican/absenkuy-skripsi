<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CutiController extends Controller
{
    public function listCuti()
    {
        $cuti = Cuti::all();

        return response()->json([
            'status' => 'success',
            'message' => 'List Cuti',
            'data' => $cuti
        ]);
    }

    public function pengajuanCuti(Request $request)
    {
        try {
            DB::beginTransaction();

            $cuti = Cuti::create([
                'user_id' => auth()->user()->user_id,
                'tanggal_cuti' => $request->tanggal_cuti,
                'alasan_cuti' => $request->alasan_cuti,
                'lama_cuti' => $request->lama_cuti,
                'status_pengajuan' => 0,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan Cuti Berhasil',
                'data' => $cuti
            ]);
        } catch (Error $error) {
            DB::rollback();
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan Cuti Gagal',
                'data' => $cuti
            ], 400);
        }
    }
}
