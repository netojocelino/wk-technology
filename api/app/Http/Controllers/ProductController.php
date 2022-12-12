<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function postProduct (Request $request) {
        try {
            $request->validate([
                'name' => 'required|string',
                'unit_price' => 'required|numeric',
            ]);

            $data = $request->only([
                'name',
                'unit_price',
            ]);

            $product = new Product($data);
            $product->save();

            return response($product, 201);
        } catch (ValidationException $exception) {
            return response([
                'error' => $exception->getMessage(),
            ], 422);
        } catch (\Exception $exception) {
            return response([
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function getProducts (Request $request)
    {
        try {
            $products = Product::all()->toArray();

            return response($products, 200);
        } catch (\Exception $exception) {
            return response([
                'message' => 'Products cannot be retrived',
            ], 500);
        }
    }

    public function getProduct (Request $request, string $id)
    {
        try {
            $product = Product::findOrFail($id);

            return response($product, 200);
        } catch (\Exception $exception) {
            return response([
                'message' => 'Product cannot exists.',
            ], 404);
        }
    }

}
