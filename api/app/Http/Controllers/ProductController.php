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
}
