<?php

namespace App\Http\Controllers;

use App\Order;
use App\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function bulanan(Request $request)
    {
        $data = DB::select("SELECT js.nama AS jenis_sarana,
                (SELECT SUM(target) FROM program_kerjas WHERE jenis_sarana_id = js.id AND tahun = ? AND bulan = ?) AS target,
                (SELECT COUNT(id) FROM orders WHERE jenis_sarana_id = js.id AND YEAR(tanggal_masuk) = ? AND MONTH(tanggal_masuk) = ? AND status = 0) AS terdaftar,
                (SELECT COUNT(id) FROM orders WHERE jenis_sarana_id = js.id AND YEAR(tanggal_masuk) = ? AND MONTH(tanggal_masuk) = ? AND status = 10) AS dalam_pengerjaan,
                (SELECT COUNT(id) FROM orders WHERE jenis_sarana_id = js.id AND YEAR(tanggal_masuk) = ? AND MONTH(tanggal_masuk) = ? AND status = 20) AS selesai
            FROM jenis_saranas js", [
            $request->tahun, $request->bulan,
            $request->tahun, $request->bulan,
            $request->tahun, $request->bulan,
            $request->tahun, $request->bulan,
        ]);

        return array_map(function ($item) {
            $item->belum_masuk = $item->target - $item->terdaftar - $item->dalam_pengerjaan - $item->selesai;
            return $item;
        }, $data);
    }

    public function tahunan(Request $request)
    {
        $request->validate(['tahun' => 'required']);

        return [
            'terdaftar' => Order::where('status', Order::STATUS_TERDAFTAR)->whereYear('tanggal_masuk', $request->tahun)->count(),
            'dalam_pengerjaan' => Order::where('status', Order::STATUS_DALAM_PENGERJAAN)->whereYear('tanggal_masuk', $request->tahun)->count(),
            'selesai' => Order::where('status', Order::STATUS_SELESAI)->whereYear('tanggal_masuk', $request->tahun)->count(),
            'target' => ProgramKerja::where('tahun', $request->tahun)->pluck('target')->sum()
        ];
    }
}
