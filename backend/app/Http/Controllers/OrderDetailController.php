<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDetailRequest;
use App\OrderDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function update(OrderDetailRequest $request, OrderDetail $orderDetail)
    {
        $orderDetail->update($request->all());
        $order = $orderDetail->order;
        $order->update([
            'prosentase_pekerjaan' => $order->orderDetail()->pluck('prosentase_pekerjaan')->avg()
        ]);

        return ['message' => 'Data telah disimpan', 'data' => $orderDetail];
    }

    public function destroy(OrderDetail $orderDetail)
    {
        $orderDetail->delete();
        $order = $orderDetail->order;
        $order->update([
            'prosentase_pekerjaan' => $order->orderDetail()->pluck('prosentase_pekerjaan')->avg()
        ]);
        return ['message' => 'Data telah dihapus'];
    }
}
