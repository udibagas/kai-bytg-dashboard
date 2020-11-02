<?php

namespace App\Http\Controllers;

use App\Dipo;
use App\Http\Requests\DipoRequest;
use Illuminate\Http\Request;

class DipoController extends Controller
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
        return Dipo::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nama', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('kode', 'LIKE', "%{$request->keyword}%");
            });
        })->orderBy('kode', 'asc')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DipoRequest $request)
    {
        $dipo = Dipo::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $dipo
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dipo  $dipo
     * @return \Illuminate\Http\Response
     */
    public function show(Dipo $dipo)
    {
        return $dipo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dipo  $dipo
     * @return \Illuminate\Http\Response
     */
    public function update(DipoRequest $request, Dipo $dipo)
    {
        $dipo->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $dipo
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dipo  $dipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dipo $dipo)
    {
        $dipo->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
