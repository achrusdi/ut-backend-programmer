<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    public function getAll()
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Category products retrieved successfully',
            'data' => CategoryProduct::all(),
        ], 200);
    }

    public function getById($id)
    {
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Category product retrieved successfully',
            'data' => CategoryProduct::find($id),
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:16'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Category product not created',
                'error' => $validator->errors(),
            ], 400);
        }

        $data = $request->all();
        $data['slug'] = \Str::slug($data['name']);

        $categoryProduct = CategoryProduct::create($data);
        return response()->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'Category product created successfully',
            'data' => $categoryProduct,
        ], 201);
    }

    public function update(Request $request, $id)
    {

        $data = $request->all();
        $data['slug'] = \Str::slug($data['name']);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'min:3', 'max:16', 'unique:category_products'],
            // 'slug' => ['required', 'unique:category_products'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'Category product not updated',
                'error' => $validator->errors(),
            ], 400);
        }

        $data = $request->all();
        $data['slug'] = \Str::slug($data['name']);

        $categoryProduct = CategoryProduct::find($id);
        if ($categoryProduct->update($data)) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Category product updated successfully',
                'data' => $categoryProduct,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Category product not updated',
        ], 400);
    }

    public function delete($id)
    {
        $categoryProduct = CategoryProduct::find($id);
        if (!$categoryProduct) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Category product not found',
            ], 404);
        }

        if ($categoryProduct->delete()) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Category product deleted successfully',
                'data' => $categoryProduct,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'code' => 400,
            'message' => 'Category product not deleted',
        ], 400);
    }
}
