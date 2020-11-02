<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProgressRequest;
use App\JenisDetailPekerjaan;
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
        if (is_numeric($request->jenis_detail_pekerjaan_id)) {
            $jenisDetailPekerjaan = JenisDetailPekerjaan::find($request->jenis_detail_pekerjaan_id);

            // kemungkinan ngisi nomor doank
            if (!$jenisDetailPekerjaan) {
                return response(['message' => 'Jenis detail pekerjaan tidak ditemukan'], 500);
            }
        } else {
            $jenisDetailPekerjaan = JenisDetailPekerjaan::firstOrCreate(
                ['nama' => $request->jenis_detail_pekerjaan_id],
                ['nama' => $request->jenis_detail_pekerjaan_id]
            );
        }

        $data = array_merge($request->all(), [
            'user_id' => auth()->user()->id,
            'jenis_detail_pekerjaan_id' => $jenisDetailPekerjaan->id // update dengan data yg baru
        ]);

        $orderProgress = OrderProgress::create($data);
        $order = $orderProgress->order;

        $order->orderDetail()
            ->updateOrCreate(
                ['jenis_detail_pekerjaan_id' => $request->jenis_detail_pekerjaan_id],
                $data
            );

        $prosentase = $order->orderDetail()->pluck('prosentase_pekerjaan')->avg();

        $order->update([
            // 'status' => $request->status,
            'tanggal_keluar' => $request->tanggal_keluar,
            'prosentase_pekerjaan' => $prosentase,
            'status' => $prosentase < 100 ? Order::STATUS_DALAM_PENGERJAAN : Order::STATUS_SELESAI
        ]);

        return [
            'message' => 'Data telah disimpan',
            'data' => $orderProgress
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderProgress  $orderProgress
     * @return \Illuminate\Http\Response
     */
    public function update(OrderProgressRequest $request, OrderProgress $orderProgress)
    {
        $orderProgress->update($request->all());

        return [
            'message' => 'Data telah disimpan',
            'data' => $orderProgress
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderProgress  $orderProgress
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderProgress $orderProgress)
    {
        $orderProgress->delete();

        return ['message' => 'Data telah dihapus'];
    }
}
