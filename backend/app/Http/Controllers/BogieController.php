<?php

namespace App\Http\Controllers;

use App\Bogie;
use App\Http\Requests\BogieRequest;
use Illuminate\Http\Request;

class BogieController extends Controller
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
        return Bogie::when($request->keyword, function ($q) use ($request) {
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
    public function store(BogieRequest $request)
    {
        $bogie = Bogie::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $bogie
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bogie  $bogie
     * @return \Illuminate\Http\Response
     */
    public function update(BogieRequest $request, Bogie $bogie)
    {
        $bogie->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $bogie
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bogie  $bogie
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bogie $bogie)
    {
        $bogie->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
