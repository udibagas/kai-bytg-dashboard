<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramKerjaRequest;
use App\ProgramKerja;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:10,30']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ProgramKerja::when($request->tahun, function ($q) use ($request) {
            $q->where('tahun', $request->tahun);
        })->when($request->bulan, function ($q) use ($request) {
            $q->where('bulan', $request->bulan);
        })->when($request->jenis_sarana_id, function ($q) use ($request) {
            $q->where('jenis_sarana_id', $request->jenis_sarana_id);
        })->when($request->jenis_pekerjaan_id, function ($q) use ($request) {
            $q->where('jenis_pekerjaan_id', $request->jenis_pekerjaan_id);
        })->orderBy('bulan', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ProgramKerja::where('tahun', $request->tahun)->delete();

        $data = [];

        foreach ($request->program_kerja as $programKerja) {
            foreach ($programKerja['program'] as $program) {
                foreach ($program['target'] as $bulan => $target) {
                    if ($bulan == 0) continue;

                    $data[] = [
                        'tahun' => $request->tahun,
                        'jenis_sarana_id' => $programKerja['jenis_sarana_id'],
                        'jenis_pekerjaan_id' => $program['jenis_pekerjaan_id'],
                        'bulan' => $bulan,
                        'target' => $target
                    ];
                }
            }
        }

        ProgramKerja::insert($data);

        return [
            'message' => 'Data telah disimpan',
            'data' => $programKerja
        ];
    }
}
