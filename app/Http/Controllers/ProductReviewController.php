<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product_Review;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'user_id' => 'required|integer|exists:users,id',
            'comment' => 'sometimes|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $productReviews = Product_Review::create($request->only([
            'product_id',
            'user_id',
            'comment',
            'rating',
        ]));
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
