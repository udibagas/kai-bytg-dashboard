<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisDetailPekerjaanRequest;
use App\JenisDetailPekerjaan;
use Illuminate\Http\Request;

class JenisDetailPekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return JenisDetailPekerjaan::when($request->keyword, function ($q) use ($request) {
            $q->where('nama', 'LIKE', "%{$request->keyword}%");
        })->when($request->hidden === 'false', function ($q) {
            $q->where('hidden', 0);
        })->orderBy('nama', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisDetailPekerjaanRequest $request)
    {
        $jenisDetailPekerjaan = JenisDetailPekerjaan::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisDetailPekerjaan
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisDetailPekerjaan  $jenisDetailPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function update(JenisDetailPekerjaanRequest $request, JenisDetailPekerjaan $jenisDetailPekerjaan)
    {
        $jenisDetailPekerjaan->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisDetailPekerjaan
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisDetailPekerjaan  $jenisDetailPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisDetailPekerjaan $jenisDetailPekerjaan)
    {
        $jenisDetailPekerjaan->delete();
    }
}
