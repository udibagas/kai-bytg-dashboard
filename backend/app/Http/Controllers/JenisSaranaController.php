<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisSaranaRequest;
use App\JenisSarana;
use Illuminate\Http\Request;

class JenisSaranaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return JenisSarana::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('kode', 'LIKE', "%{$request->keyword}%");
            });
        })->orderBy('nama', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisSaranaRequest $request)
    {
        $jenisSarana = JenisSarana::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisSarana
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JenisSarana  $jenisSarana
     * @return \Illuminate\Http\Response
     */
    public function update(JenisSaranaRequest $request, JenisSarana $jenisSarana)
    {
        $jenisSarana->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $jenisSarana
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JenisSarana  $jenisSarana
     * @return \Illuminate\Http\Response
     */
    public function destroy(JenisSarana $jenisSarana)
    {
        $jenisSarana->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
