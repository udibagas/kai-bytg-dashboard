<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SaranaCollection extends ResourceCollection
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
                    'dipo' => $item->dipo->nama,
                    'jenis_sarana' => $item->jenisSarana->nama,
                    'bogie' => $item->bogie ? $item->bogie->kode : ''
                ]);
            })
        ];
    }
}
