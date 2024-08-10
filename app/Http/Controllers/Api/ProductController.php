<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Products retrieved successfully',
            'data' => Product::all(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = CategoryProduct::where('name', $request->category_product_name)->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Category product not found'
            ], 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'category_product_id' => $category->id
        ]);

        if ($product) {
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Product not created',
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Product retrieved successfully',
            'data' => Product::with('categoryProduct')->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = CategoryProduct::where('name', $request->category_product_name)->first();

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Category product not found'
            ], 400);
        }

        $product = Product::find($id);

        if ($product) {
            $product->name = $request->name;
            $product->price = $request->price;
            $product->category_product_id = $category->id;
            $product->save();
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Product updated successfully',
                'data' => $product,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Product not updated',
        ], 400);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Product not found',
            ], 404);
        }

        if ($product->delete()) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Product deleted successfully',
                'data' => $product,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Product not deleted',
        ], 400);
    }
}
