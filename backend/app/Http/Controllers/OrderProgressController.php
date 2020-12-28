<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProgressRequest;
use App\Order;
use App\OrderProgress;
use Illuminate\Http\Request;

class OrderProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:10,20,30']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return OrderProgress::when($request->order_id, function ($q) use ($request) {
            return $q->where('order_id', $request->order_id);
        })->orderBy('created_at', 'desc')->paginate($request->pageSize);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderProgressRequest $request)
    {
        $data = array_merge($request->all(), ['user_id' => auth()->user()->id,]);
        $orderProgress = OrderProgress::create($data);
        $order = $orderProgress->order;

        foreach ($request->checklist as $c) {
            $order->orderDetail()->updateOrCreate(['jenis_detail_pekerjaan_id' => $c['id']], [
                'jenis_detail_pekerjaan_id' => $c['id'],
                'urutan' => $c['urutan'],
                'nama' => $c['nama'],
                'bobot' => $c['bobot'],
                'check' => $c['check']
            ]);
        }

        $order->update([
            'tanggal_keluar' => $request->tanggal_keluar,
            'prosentase_pekerjaan' => $request->prosentase_pekerjaan,
            'status' => $request->prosentase_pekerjaan < 100 ? Order::STATUS_DALAM_PENGERJAAN : Order::STATUS_SELESAI,
            'keterangan' => $request->keterangan,
            'posisi' => $request->posisi,
        ]);

        return [
            'message' => 'Data telah disimpan',
            'data' => $orderProgress
        ];
    }
}
