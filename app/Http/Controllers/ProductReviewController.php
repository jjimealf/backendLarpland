<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Product_Review;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productReviews = Product_Review::all();
        return response()->json($productReviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productReviews = Product_Review::create($request->all());
        return response()->json($productReviews, 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $productReviews = Product_Review::where('product_id', $id)->get();
        return response()->json($productReviews);
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
