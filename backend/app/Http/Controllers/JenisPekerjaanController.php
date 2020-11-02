<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisPekerjaanRequest;
use App\JenisPekerjaan;
use App\User;
use Illuminate\Http\Request;

class JenisPekerjaanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:10,20,30'])->except(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return JenisPekerjaan::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('keterangan', 'LIKE', "%{$request->keyword}%");
            });
        })->orderBy('nama', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisPekerjaanRequest $request)
    {
        $jenisPekerjaan = JenisPekerjaan::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisPekerjaan
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisPekerjaan  $jenisPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function update(JenisPekerjaanRequest $request, JenisPekerjaan $jenisPekerjaan)
    {
        $jenisPekerjaan->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisPekerjaan
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisPekerjaan  $jenisPekerjaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisPekerjaan $jenisPekerjaan)
    {
        $jenisPekerjaan->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
