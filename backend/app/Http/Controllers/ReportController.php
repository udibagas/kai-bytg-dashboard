<?php

namespace App\Http\Controllers;

use App\JenisSarana;
use App\Order;
use App\ProgramKerja;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $request->validate([
            'tahun' => 'required|numeric',
            'bulan' => 'required|numeric|min:1,max:12'
        ]);

        return JenisSarana::get()->map(function ($item) use ($request) {
            return [
                'jenis_sarana' => $item->nama,

                'program' => ProgramKerja::chart()
                    ->where('jenis_sarana_id', $item->id)
                    ->where('tahun', $request->tahun)
                    ->where('bulan', $request->bulan)
                    ->chart()->pluck('target')->sum(),

                'realisasi' => Order::chart()
                    ->where('jenis_sarana_id', $item->id)
                    ->where('status', Order::STATUS_SELESAI)
                    ->whereYear('tanggal_keluar', $request->tahun)
                    ->whereMonth('tanggal_keluar', $request->bulan)
                    ->chart()->count(),

                'proses' => Order::chart()
                    ->where('jenis_sarana_id', $item->id)
                    ->where('status', '!=', Order::STATUS_SELESAI)
                    ->whereYear('tanggal_masuk', $request->tahun)
                    ->whereMonth('tanggal_masuk', $request->bulan)
                    ->chart()->count(),
            ];
        });
    }

    public function annualReport(Request $request)
    {
        $request->validate(['tahun' => 'required|numeric']);

        $report = [];

        foreach (range(1, 12) as $bulan) {
            $report[] = [
                'bulan' => date('M', mktime(0, 0, 0, $bulan, 10)),

                'program' => ProgramKerja::chart()
                    ->where('tahun', $request->tahun)
                    ->where('bulan', $bulan)
                    ->chart()->pluck('target')->sum(),

                'realisasi' => Order::chart()
                    ->where('status', Order::STATUS_SELESAI)
                    ->whereYear('tanggal_keluar', $request->tahun)
                    ->whereMonth('tanggal_keluar', $bulan)
                    ->chart()->count(),

                'proses' => Order::chart()
                    ->where('status', '!=', Order::STATUS_SELESAI)
                    ->whereYear('tanggal_masuk', $request->tahun)
                    ->whereMonth('tanggal_masuk', $bulan)
                    ->chart()->count(),
            ];
        }

        return $report;
    }
}
