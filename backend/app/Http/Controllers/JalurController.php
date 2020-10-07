<?php

namespace App\Http\Controllers;

use App\Jalur;
use Illuminate\Http\Request;

class JalurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Jalur::when($request->keyword, function ($q) use ($request) {
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
    public function store(Request $request)
    {
        $jalur = Jalur::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jalur
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jalur  $jalur
     * @return \Illuminate\Http\Response
     */
    public function show(Jalur $jalur)
    {
        return $jalur;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jalur  $jalur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jalur $jalur)
    {
        $jalur->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jalur
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jalur  $jalur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jalur $jalur)
    {
        $jalur->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
