<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return array_merge($item->toArray(), [
                    'nomor_sarana' => $item->sarana ? $item->sarana->nomor : '',
                    'jenis_pekerjaan' => $item->jenisPekerjaan->kode,
                    'dipo' => $item->dipo->kode,
                    'jenis_sarana' => $item->jenisSarana->kode,
                    'jalur' => $item->jalur->nama
                ]);
            })
        ];
    }
}
