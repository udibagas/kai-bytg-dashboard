<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDetailRequest;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function update(OrderDetailRequest $request, OrderDetail $orderDetail)
    {
        $orderDetail->update($request->all());
        $order = $orderDetail->order;
        $prosentase = $order->orderDetail()->pluck('prosentase_pekerjaan')->avg();

        $order->update([
            'prosentase_pekerjaan' => $prosentase,
            'status' => $prosentase < 100 ? Order::STATUS_DALAM_PENGERJAAN : Order::STATUS_SELESAI
        ]);

        return ['message' => 'Data telah disimpan', 'data' => $orderDetail];
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        $order = $orderDetail->order;
        $prosentase = $order->orderDetail()->pluck('prosentase_pekerjaan')->avg();

        $order->update([
            'prosentase_pekerjaan' => $prosentase,
            'status' => $prosentase < 100 ? Order::STATUS_DALAM_PENGERJAAN : Order::STATUS_SELESAI
        ]);

        return ['message' => 'Data telah dihapus'];
    }
}
