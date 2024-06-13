<?php

namespace App\Http\Controllers;

use App\Models\Detail_Order;
use Illuminate\Http\Request;

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
        $order = new Detail_Order($request->all());
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
