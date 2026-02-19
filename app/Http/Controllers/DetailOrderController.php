<?php

namespace App\Http\Controllers;

use App\Models\Detail_Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Detail_Order::all();
        return response()->json($order);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $order = new Detail_Order($request->only([
            'order_id',
            'product_id',
            'cantidad',
            'precio_unitario',
        ]));
        $order->save();
        return response()->json($order, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Detail_Order::findOrfail($id);
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
