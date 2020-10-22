<?php

namespace App\Http\Controllers;

use App\Dipo;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderCollection;
use App\JenisPekerjaan;
use App\JenisSarana;
use App\Order;
use App\Sarana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = Order::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nomor', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('keterangan', 'LIKE', "%{$request->keyword}%")
                    ->orWhereHas('sarana', function ($q) use ($request) {
                        $q->where('nomor', 'LIKE', "%{$request->keyword}%");
                    });
            });
        })->when($request->dateRange, function ($q) use ($request) {
            $q->whereBetween('tanggal_keluar', $request->dateRange);
        })->when($request->tanggal_masuk, function ($q) use ($request) {
            $q->whereBetween('tanggal_masuk', $request->tanggal_masuk);
        })->when($request->jenis_sarana_id, function ($q) use ($request) {
            $q->whereIn('jenis_sarana_id', $request->jenis_sarana_id);
        })->when($request->dipo_id, function ($q) use ($request) {
            $q->whereIn('dipo_id', $request->dipo_id);
        })->when($request->jalur_id, function ($q) use ($request) {
            $q->whereIn('jalur_id', $request->jalur_id);
        })->when($request->jenis_pekerjaan_id, function ($q) use ($request) {
            $q->whereIn('jenis_pekerjaan_id', $request->jenis_pekerjaan_id);
        })->when($request->status, function ($q) use ($request) {
            $q->whereIn('status', $request->status);
        })->when($request->tahun, function ($q) use ($request) {
            $q->whereYear('tanggal_masuk', $request->tahun);
        })->when($request->bulan, function ($q) use ($request) {
            $q->whereMonth('tanggal_masuk', $request->bulan);
        })->orderBy($request->sort ?: 'tanggal_masuk', $request->order == 'ascending' ? 'asc' : 'desc')->paginate($request->per_page);

        return new OrderCollection($resource);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        // dikhawatirkan bentrok dengan nomor
        $sarana = Sarana::find($request->sarana_id);

        // if sarana not registered create new one
        if (!$sarana) {
            $sarana = Sarana::firstOrCreate(['nomor' => $request->sarana_id], [
                'nomor' => $request->sarana_id,
                'nomor_lama' => $request->nomor_lama,
                'dipo_id' => $request->dipo_id,
                'jenis_sarana_id' => $request->jenis_sarana_id
            ]);
        }

        $order = Order::create(array_merge($request->all(), [
            'user_id' => auth()->user()->id,
            'sarana_id' => $sarana->id,
        ]));

        return [
            'message' => 'Data telah disimpan',
            'data' => $order
        ];
    }

    public function import(Request $request)
    {
        $request->validate(['rows' => 'required']);

        // return array_filter($request->rows, function ($row) {
        //     return !$this->parseExcelDate($row['tanggal_masuk']);
        // });

        DB::beginTransaction();

        foreach ($request->rows as $row) {
            $jenisSarana = JenisSarana::where('kode', $row['jenis_sarana'])->first();
            $dipo = Dipo::where('kode', $row['dipo'])->first();
            $jenisPekerjaan = JenisPekerjaan::where('kode', $row['jenis_pekerjaan'])->first();
            $sarana = Sarana::where('nomor', $row['nomor_sarana'])->first();

            if (!$jenisSarana) {
                DB::rollBack();
                return response(['message' => "Jenis sarana {$row['jenis_sarana']} tidak terdaftar"], 500);
            }

            if (!$dipo) {
                DB::rollBack();
                return response(['message' => "Dipo {$row['dipo']} tidak terdaftar"], 500);
            }

            if (!$jenisPekerjaan) {
                DB::rollBack();
                return response(['message' => "Jenis pekerjaan {$row['jenis_pekerjaan']} tidak terdaftar"], 500);
            }

            if (!$sarana) {
                $sarana = Sarana::create([
                    'nomor' => $row['nomor_sarana'],
                    'dipo_id' => $dipo->id,
                    'jenis_sarana_id' => $jenisSarana->id
                ]);
            }

            Order::updateOrCreate(
                [
                    'sarana_id' => $sarana->id,
                    'jenis_pekerjaan_id' => $jenisPekerjaan->id,
                    'tanggal_masuk' => $this->parseExcelDate($row['tanggal_masuk'])
                ],
                [
                    'nomor' => '-',
                    'sarana_id' => $sarana->id,
                    'jenis_sarana_id' => $jenisSarana->id,
                    'dipo_id' => $dipo->id,
                    'jenis_pekerjaan_id' => $jenisPekerjaan->id,
                    'tanggal_masuk' => $this->parseExcelDate($row['tanggal_masuk']),
                    'tanggal_keluar' => $this->parseExcelDate(isset($row['tanggal_keluar']) ? $row['tanggal_keluar'] : null),
                    'keterangan' => $row['keterangan'],
                    'prosentase_pekerjaan' => $row['prosentase_pekerjaan'] * 100,
                    'status' => $row['prosentase_pekerjaan'] * 100 < 100 ? Order::STATUS_DALAM_PENGERJAAN : Order::STATUS_SELESAI,
                    'user_id' => auth()->user()->id
                ]
            );
        }

        DB::commit();

        return ['message' => 'Data berhasil diimport'];
    }

    protected function parseExcelDate($date)
    {
        if ($date === null) {
            return null;
        }

        if (!is_numeric($date)) {
            return date('Y-m-d', strtotime($date));
        }

        $unix_date = ($date - 25569) * 86400;
        $date = 25569 + ($unix_date / 86400);
        $unix_date = ($date - 25569) * 86400;
        return gmdate("Y-m-d", $unix_date);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return $order->load([
            'orderDetail',
            'orderProgress',
            'user',
            'sarana',
            'jenisSarana',
            'dipo',
            'jenisPekerjaan'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        // dikhawatirkan bentrok dengan nomor
        $sarana = Sarana::find($request->sarana_id);

        // if sarana not registered create new one
        if (!$sarana) {
            $sarana = Sarana::firstOrCreate(['nomor' => $request->sarana_id], [
                'nomor' => $request->sarana_id,
                'nomor_lama' => $request->nomor_lama,
                'dipo_id' => $request->dipo_id,
                'jenis_sarana_id' => $request->jenis_sarana_id
            ]);
        }

        $order->update(array_merge($request->all(), [
            'sarana_id' => $sarana->id,
        ]));

        return [
            'message' => 'Data telah disimpan',
            'data' => $order
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->delete();
            $order->orderDetail()->delete();
            $order->orderProgress()->delete();
        });

        return ['message' => 'Data telah dihapus'];
    }

    public function export(Request $request)
    {
        return Order::when($request->keyword, function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->where('nomor', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('keterangan', 'LIKE', "%{$request->keyword}%")
                    ->orWhereHas('sarana', function ($q) use ($request) {
                        $q->where('nomor', 'LIKE', "%{$request->keyword}%");
                    });
            });
        })->when($request->dateRange, function ($q) use ($request) {
            $q->whereBetween('tanggal_keluar', $request->dateRange);
        })->when($request->tanggal_masuk, function ($q) use ($request) {
            $q->whereBetween('tanggal_masuk', $request->tanggal_masuk);
        })->when($request->jenis_sarana_id, function ($q) use ($request) {
            $q->whereIn('jenis_sarana_id', $request->jenis_sarana_id);
        })->when($request->dipo_id, function ($q) use ($request) {
            $q->whereIn('dipo_id', $request->dipo_id);
        })->when($request->jalur_id, function ($q) use ($request) {
            $q->whereIn('jalur_id', $request->jalur_id);
        })->when($request->jenis_pekerjaan_id, function ($q) use ($request) {
            $q->whereIn('jenis_pekerjaan_id', $request->jenis_pekerjaan_id);
        })->when($request->status, function ($q) use ($request) {
            $q->whereIn('status', $request->status);
        })->when($request->tahun, function ($q) use ($request) {
            $q->whereYear('tanggal_masuk', $request->tahun);
        })->when($request->bulan, function ($q) use ($request) {
            $q->whereMonth('tanggal_masuk', $request->bulan);
        })->orderBy('updated_at', 'desc')->get()->map(function ($item, $index) {
            return [
                'NO' => $index + 1,
                'Jenis Sarana' => $item->jenisSarana->kode,
                'Nomor Sarana' => $item->sarana ? $item->sarana->nomor : '',
                'Dipo' => $item->dipo->kode,
                'Pekerjaan' => $item->jenisPekerjaan->kode,
                'Masuk' => $item->tanggal_masuk,
                'Keluar' => $item->tanggal_keluar,
                'Keterangan' => $item->keterangan ?: ''
            ];
        });
    }
}
