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
        $data = DB::select("SELECT COUNT(o.id) AS realisasi,
                (SELECT SUM(target) FROM program_kerjas WHERE tahun = ?) AS target
            FROM orders o
            WHERE YEAR(o.tanggal_masuk) = ? AND o.status = ?", [$request->tahun, $request->tahun, Order::STATUS_SELESAI]);

        return array_map(function ($item) {
            $item->kurang = $item->target - $item->realisasi;
            return $item;
        }, $data);
    }
}
