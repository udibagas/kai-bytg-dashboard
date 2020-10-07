<?php

namespace App\Http\Controllers;

use App\Http\Requests\StasiunRequest;
use App\Stasiun;
use Illuminate\Http\Request;

class StasiunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Stasiun::when($request->keyword, function ($q) use ($request) {
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
    public function store(StasiunRequest $request)
    {
        $stasiun = Stasiun::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $stasiun
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stasiun  $stasiun
     * @return \Illuminate\Http\Response
     */
    public function update(StasiunRequest $request, Stasiun $stasiun)
    {
        $stasiun->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $stasiun
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stasiun  $stasiun
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stasiun $stasiun)
    {
        $stasiun->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
