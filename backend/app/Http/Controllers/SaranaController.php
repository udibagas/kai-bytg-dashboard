<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaranaRequest;
use App\Http\Resources\SaranaCollection;
use App\Sarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = Sarana::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nomor', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('nomor_lama', 'LIKE', "%{$request->keyword}%");
            });
        })->orderBy('nomor', 'asc')->paginate($request->per_page);

        return new SaranaCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaranaRequest $request)
    {
        $sarana = Sarana::create($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $sarana
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sarana  $sarana
     * @return \Illuminate\Http\Response
     */
    public function show(Sarana $sarana)
    {
        return $sarana;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sarana  $sarana
     * @return \Illuminate\Http\Response
     */
    public function update(SaranaRequest $request, Sarana $sarana)
    {
        $sarana->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $sarana
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sarana  $sarana
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sarana $sarana)
    {
        $sarana->delete();

        return ['message' => 'Data telah dihapus'];
    }

    public function getList(Request $request)
    {
        return Sarana::when($request->nomor, function ($q) use ($request) {
            $q->where('nomor', 'LIKE', "%{$request->nomor}%");
        })->orderBy('nomor', 'asc')->get();
    }
}
