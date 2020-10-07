<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function bulanan(Request $request)
    {
        return DB::select("SELECT js.nama AS jenis_sarana,
                (SELECT SUM(target) FROM program_kerjas WHERE jenis_sarana_id = js.id AND tahun = ? AND bulan = ?) AS target,
                (SELECT COUNT(id) FROM orders WHERE jenis_sarana_id = js.id AND YEAR(tanggal_masuk) = ? AND MONTH(tanggal_masuk) = ?) AS realisasi
            FROM jenis_saranas js", [$request->tahun, $request->bulan, $request->tahun, $request->bulan]);
    }

    public function tahunan(Request $request)
    {
        return DB::select("SELECT COUNT(o.id) AS realisasi,
                (SELECT SUM(target) FROM program_kerjas WHERE tahun = ?) AS target
            FROM orders o
            WHERE YEAR(o.tanggal_masuk) = ?", [$request->tahun, $request->tahun]);
    }
}
